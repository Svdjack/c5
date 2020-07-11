window.Paginator = Backbone.View.extend({

    className: "pagination pagination-centered",

    initialize: function () {
        this.model.bind("reset", this.render, this);
        this.render();
    },

    render: function () {
        var items = this.model.models;
        var pageCount = this.model.totalPages;
        var currentPage = parseInt(this.options.page);
        var type = this.options.type;
        $(this.el).html('<ul />');
        type = type.replace('-','/');
        var pagination = [1, pageCount];
        for (var i = 0; i <= 5; i++) {
            if (currentPage - 2 + i > 1 && currentPage - 2 + i < pageCount) {
                pagination.push(currentPage - 2 + i)
            }
        }

        function sortNumber(a, b) {
            return a - b;
        }

        pagination.sort(sortNumber);

        if (pageCount < 10) {
            for (var i = 0; i < pageCount; i++) {
                console.log(parseInt(this.options.page));
                console.log(i);
                $('ul', this.el).append("<div" + ((i + 1) === parseInt(this.options.page) ? " class='pager-current'" : " class='pager-item'") + "><a href='#" + type + "/page/" + (i + 1) + "'>" + (i + 1) + "</a></div>");
            }
        }
        else {
            var ellipsis = '<div class="pager-item">...</div>';
            for (var i = 0, paginationCount = pagination.length; i < paginationCount; i++) {
                if (pagination[i] - page > 1)
                    $('ul', this.el).append(ellipsis);
                var page = pagination[i];
                $('ul', this.el).append("<div" + ((page) === currentPage ? " class='pager-current'" : " class='pager-item'") + "><a" +
                    " href='#" + type + "/page/" + (page) + "'>" + (page) + "</a></div>");
            }
        }

        return this;
    }
});