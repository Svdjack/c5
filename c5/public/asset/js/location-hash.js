jQuery(document).ready(() => {
    let func = () => {
        let hash = document.location.hash;

        if (!hash) {
            return;
        }

console.log('a[name="' + hash.substr(1) + '"]');
        let e = jQuery('a[name="' + hash.substr(1) + '"]');

        if (e.length <= 0) {
            return;
        }

        jQuery("html ,body").animate({
            scrollTop: e.offset().top
        }, 800);
    };
    func();
    setTimeout(func, 400);
});