<?php

namespace wMVC\Controllers\Admin;

use PropelModel\CommentQuery;
use PropelModel\ContactQuery;
use PropelModel\FirmQuery;
use wMVC\Controllers\abstractAdmin;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Core\Model;
use wMVC\Entity\City;
use wMVC\Controllers\Kotel;
use Propel\Runtime\Propel;
use PropelModel\Map\FirmTableMap;

Class Review extends abstractAdmin
{

    public function reviewCounter()
    {
        self::requireAdmin();
        $types = ['reviews', 'reviews-approved', 'reviews-deleted'];

        $counters = [];
        foreach ($types as $type) {
            $reviews = CommentQuery::create();
            switch ($type) {
                case 'reviews-new':
                    $reviews->filterByEdited(0)->filterByActive(1)->addAscendingOrderByColumn('date');
                    break;

                case 'reviews-approved':
                    $reviews->filterByEdited(1)->filterByActive(1);
                    break;

                case 'reviews-deleted':
                    $reviews->filterByActive(0);
                    break;

                case 'reviews':
                    $reviews->filterByEdited(0)->filterByActive(1);
                    break;

                default:
                    throw new SystemExit('not found', 404);
                    break;
            }
            $counters[$type] = $reviews->count();
        }

        self::response($counters);
    }

    public function getReviewsList($type, $page = 0, $search = '')
    {
        self::requireAdmin();
        $reviews = CommentQuery::create();
        switch ($type) {
            case 'reviews-new':
                $reviews->filterByEdited(0)->filterByActive(1)->addAscendingOrderByColumn('date');
                break;

            case 'reviews-approved':
                $reviews->filterByEdited(1)->filterByActive(1);
                break;

            case 'reviews-deleted':
                $reviews->filterByActive(0);
                break;

            default:
                throw new SystemExit('not found', 404);
                break;
        }
        
        if ($search) {
            $reviews->joinFirm()->addJoinCondition('Firm', 'Firm.name like ?', $search . '%');
        }

        $con = Propel::getWriteConnection(FirmTableMap::DATABASE_NAME);
        $con->useDebug(true);

        $reviews = $reviews->paginate($page, 100);
        
        $queryDebug = $con->getLastExecutedQuery();
        
        $totalPages = $reviews->getLastPage();
        $data = [];
        foreach ($reviews as $review) {
            $review_array = $review->toArray();
            
            if ($review->getFirm()) {
                $firm = $review->getFirm();
                $review_array['firm_url'] = $firm->getAlias();
                $review_array['firm_id'] = $firm->getId();
                $review_array['firm_name'] = $firm->getName();
            } else {
                $review_array['firm_url'] = '#';
                $review_array['firm_id'] = 0;
                $review_array['firm_name'] = 'Этой фирмы нет';
            }

            $review_array['Text'] = \htmlspecialchars($review_array['Text']);
            $data[] = array_change_key_case($review_array, CASE_LOWER);
        }

        $currentPage = $page;
        $result = array('data' => $data, 'currentPage' => $currentPage, 'totalPages' => $totalPages);
        if (DEBUG) {
            $result['query'] = $queryDebug;
        }
        self::response($result);

    }

    public function getReview($id)
    {
        self::requireAdmin();
        $comment = CommentQuery::create()->findPk($id);
        $firm = $comment->getFirm();
        $result = array_change_key_case($comment->toArray(), CASE_LOWER);
        $result['firm_info'] = array_change_key_case($firm->toArray(), CASE_LOWER);
        $result['firm_info']['url'] = $firm->getAlias();

        self::response($result);
    }

    public function saveReview($id)
    {
        self::requireAdmin();
        $comment = CommentQuery::create()->findPk($id);
        if (!$comment) {
            self::response(array('error' => 'Нет такого коментария...'), 500);
        }

        /*
         * Send to Comment Kotel
         */
        $output = Kotel::outcomingComment($comment);

        /**
         * debug
         */
        if (isset($_COOKIE['__DBG__'])){
            print 'Ответ из котла '.$output['answer'].'<br>';
            print 'url фирмы в запросе '.$output['request']['firm_url'].'<br>';
            print 'текст комментария '.$output['request']['text'].'<br>';
            print 'коммент не обновлен, т.к. у вас включен дебаг, выключите дебаг и сохраните снова';
            die;
        }

        $content = (array)json_decode(self::getInputContent());
        $comment->setModerationTime(time());
        $comment->setEdited(1);
        $comment->setText($content['text']);
        $comment->setActive($content['active']);
        $comment->setUser($content['user']);
        $comment->save();
        self::response(array('ok' => 1));
    }

    public function deleteReview($id)
    {
        self::requireAdmin();
        $comment = CommentQuery::create()->findPk($id);
        if (!$comment) {
            self::response(array('error' => 'Нет такого коментария...'), 500);
        }
        $comment->setActive(0);
        $comment->setModerationTime(time());
        $comment->save();
        self::response(array('ok' => 1));
    }


}