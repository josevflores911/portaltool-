(function() {
    function randint(min, max) {
        return (Math.random() * (max - min + 1) + min) | 0;
    }

    function determineOverflow(content, container) {
        var containerMetrics = container.getBoundingClientRect();
        var containerMetricsRight = Math.floor(containerMetrics.right);
        var containerMetricsLeft = Math.floor(containerMetrics.left);
        var contentMetrics = content.getBoundingClientRect();
        var contentMetricsRight = Math.floor(contentMetrics.right);
        var contentMetricsLeft = Math.floor(contentMetrics.left);
         if (containerMetricsLeft > contentMetricsLeft && containerMetricsRight < contentMetricsRight) {
            return "both";
        } else if (contentMetricsLeft < containerMetricsLeft) {
            return "left";
        } else if (contentMetricsRight > containerMetricsRight) {
            return "right";
        } else {
            return "none";
        }
    }

    var pNav = document.getElementById("pNav");
    var pContent = document.getElementById("pContent");
    
    pNav.setAttribute("data-overflowing", determineOverflow(pContent, pNav));
    
    var last_known_scroll_position = 0;
    var ticking = false;
    
    function doSomething(scroll_pos) {
        pNav.setAttribute("data-overflowing", determineOverflow(pContent, pNav));
    }
    
    pNav.addEventListener("scroll", function() {
        last_known_scroll_position = window.scrollY;
        if (!ticking) {
            window.requestAnimationFrame(function() {
                doSomething(last_known_scroll_position);
                ticking = false;
            });
        }
        ticking = true;
    });
    
    var NAV_SETTINGS = {
        isTravelling: false,
        direction: "",
        distance: pNav.clientWidth 
    }
    
    var tags = document.querySelectorAll('.tag');
    const colors = ['377eb8', '66a61e', '984ea3', '00d2d5', 'ff0029', 'ff7f00', 'af8d00', '7f80cd', '88ccee', '44aa99'];
    tags.forEach(tag => {
        tag.style.backgroundColor = `#${colors[randint(0, colors.length - 1)]}`;
    })
    // Out advancer buttons
    var leftBtn = document.getElementById("pNavBtnLeft");
    var rightBtn = document.getElementById("pNavBtnRight");
    
    leftBtn.addEventListener("click", function() {
        if (NAV_SETTINGS.isTravelling === true) {
            return;
        }

        var overflowing = determineOverflow(pContent, pNav);

        if (overflowing === "left" || overflowing === "both") {
            var availableScrollLeft = pNav.scrollLeft;
            var needToMoveWhole = availableScrollLeft < NAV_SETTINGS.distance * 2;
            pContent.style.transform = "translateX(" + (needToMoveWhole ? availableScrollLeft : NAV_SETTINGS.distance)  + "px)";
            
            pContent.classList.remove("no-transition");
            NAV_SETTINGS.direction = "left";
            NAV_SETTINGS.isTravelling = true;
        }
        pNav.setAttribute("data-overflowing", determineOverflow(pContent, pNav));
    });
    
    rightBtn.addEventListener("click", function() {
        if (NAV_SETTINGS.isTravelling === true) {
            return;
        }
        var overflowing = determineOverflow(pContent, pNav);
        if (overflowing === "right" || overflowing === "both") {
            var navBarRightEdge = pContent.getBoundingClientRect().right;
            var navBarScrollerRightEdge = pNav.getBoundingClientRect().right;
            var availableScrollRight = Math.floor(navBarRightEdge - navBarScrollerRightEdge);
            var needToMoveWhole = availableScrollRight < NAV_SETTINGS.distance * 2;

            pContent.style.transform = "translateX(-" + (needToMoveWhole ? availableScrollRight : NAV_SETTINGS.distance) + "px)";

            pContent.classList.remove("no-transition");
            NAV_SETTINGS.direction = "right";
            NAV_SETTINGS.isTravelling = true;
        }

        pNav.setAttribute("data-overflowing", determineOverflow(pContent, pNav));
    });
    
    pContent.addEventListener("transitionend", function() {
            var styleOfTransform = window.getComputedStyle(pContent, null);
            var tr = styleOfTransform.getPropertyValue("-webkit-transform") || styleOfTransform.getPropertyValue("transform");
            var amount = Math.abs(parseInt(tr.split(",")[4]) || 0);
            pContent.style.transform = "none";
            pContent.classList.add("no-transition");

            pNav.scrollLeft = NAV_SETTINGS.direction === "left" ? pNav.scrollLeft - amount : pNav.scrollLeft + amount;

            NAV_SETTINGS.isTravelling = false;
    }, false);

    window.addEventListener('resize', function() {
        pNav.setAttribute("data-overflowing", determineOverflow(pContent, pNav));
    })
})();