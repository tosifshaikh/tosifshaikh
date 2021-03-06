"use strict";
$(document).ready(function () {
    init();
});
function init() {

    togglemenu();
    if ($("body").hasClass("layout-6") || $("body").hasClass("layout-7")) {
        togglemenulayout();
    }
    menuhrres();
    var b = $(window)[0].innerWidth;
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
    $(".to-do-list input[type=checkbox]").on("click", function () {
        if ($(this).prop("checked")) {
            $(this).parent().addClass("done-task");
        } else {
            $(this).parent().removeClass("done-task");
        }
    });
    $(".mobile-menu").on("click", function () {
        var c = $(this);
        c.toggleClass("on");
    });
    $("#mobile-collapse").on("click", function () {
        if (b > 991) {
            $(".pcoded-navbar:not(.theme-horizontal)").toggleClass("navbar-collapsed");
        }
    });
    $(".mob-toggler").on("click", function () {
        $(".pcoded-header > .collapse,.pcoded-header > .container > .collapse").toggleClass("d-flex");
    });
    $(".search-btn").on("click", function () {
        var c = $(this);
        $(".main-search").addClass("open");
        if (b <= 991) {
            $(".main-search .form-control").css({ width: "90px" });
        } else {
            $(".main-search .form-control").css({ width: "150px" });
        }
    });
    $(".search-close").on("click", function () {
        var c = $(this);
        $(".main-search").removeClass("open");
        $(".main-search .form-control").css({ width: "0" });
    });
    $(".pop-search").on("click", function () {
        $(".search-bar").slideToggle("fast");
        $(".search-bar input").focus();
    });
    $(".search-bar .close").on("click", function () {
        $(".search-bar").slideToggle("fast");
    });
    if (b <= 991) {
        $(".main-search").addClass("open");
        $(".main-search .form-control").css({ width: "100px" });
    }
    if ($(".noti-body")[0]) {
        var a = new PerfectScrollbar(".notification  .noti-body", { wheelSpeed: 0.5, swipeEasing: 0, suppressScrollX: !0, wheelPropagation: 1, minScrollbarLength: 40 });
    }
    if (!$(".pcoded-navbar").hasClass("theme-horizontal")) {
        var b = $(window)[0].innerWidth;
        if ($(".navbar-content")[0]) {
            if (b < 992 || $(".pcoded-navbar").hasClass("menupos-static")) {
                var a = new PerfectScrollbar(".navbar-content", { wheelSpeed: 0.5, swipeEasing: 0, suppressScrollX: !0, wheelPropagation: 1, minScrollbarLength: 40 });
            } else {
                var a = new PerfectScrollbar(".navbar-content", { wheelSpeed: 0.5, swipeEasing: 0, suppressScrollX: !0, wheelPropagation: 1, minScrollbarLength: 40 });
            }
        }
    }
    $(".card-option .close-card").on("click", function () {
        var c = $(this);
        c.parents(".card").addClass("anim-close-card");
        c.parents(".card").animate({ "margin-bottom": "0" });
        setTimeout(function () {
            c.parents(".card").children(".card-block").slideToggle();
            c.parents(".card").children(".card-body").slideToggle();
            c.parents(".card").children(".card-header").slideToggle();
            c.parents(".card").children(".card-footer").slideToggle();
        }, 600);
        setTimeout(function () {
            c.parents(".card").remove();
        }, 1500);
    });
    $(".card-option .reload-card").on("click", function () {
        var c = $(this);
        c.parents(".card").addClass("card-load");
        c.parents(".card").append('<div class="card-loader"><i class="pct-loader1 anim-rotate"></div>');
        setTimeout(function () {
            c.parents(".card").children(".card-loader").remove();
            c.parents(".card").removeClass("card-load");
        }, 3000);
    });
    $(".card-option .minimize-card").on("click", function () {
        var e = $(this);
        var c = $(e.parents(".card"));
        var d = $(c).children(".card-block").slideToggle();
        var d = $(c).children(".card-body").slideToggle();
        if (!c.hasClass("full-card")) {
            $(c).css("height", "auto");
        }
        $(this).children("a").children("span").toggle();
    });
    $(".card-option .full-card").on("click", function () {
        var h = $(this);
        var d = $(h.parents(".card"));
        d.toggleClass("full-card");
        $(this).children("a").children("span").toggle();
        if (d.hasClass("full-card")) {
            $("body").css("overflow", "hidden");
            $("html,body").animate({ scrollTop: 0 }, 1000);
            var j = $(d, this);
            var i = j.offset();
            var c = i.left;
            var f = i.top;
            var g = $(window).height();
            var e = $(window).width();
            d.animate({ marginLeft: c - c * 2, marginTop: f - f * 2, width: e, height: g });
        } else {
            $("body").css("overflow", "");
            d.removeAttr("style");
            setTimeout(function () {
                $("html,body").animate({ scrollTop: $(d).offset().top - 70 }, 500);
            }, 400);
        }
    });
    $(".event-btn").each(function () {
        $(this).children(".spinner-border").hide();
        $(this).children(".spinner-grow").hide();
        $(this).children(".load-text").hide();
    });
    $(".event-btn").on("click", function () {
        var c = $(this);
        c.children(".spinner-border").show();
        c.children(".spinner-grow").show();
        c.children(".load-text").show();
        c.children(".btn-text").hide();
        c.attr("disabled", "true");
        setTimeout(function () {
            c.children(".spinner-border").hide();
            c.children(".spinner-grow").hide();
            c.children(".load-text").hide();
            c.children(".btn-text").show();
            c.removeAttr("disabled");
        }, 3000);
    });
    setTimeout(function () {
        $(".loader-bg").fadeOut("slow", function () {
            $(this).remove();
        });
    }, 400);
}
$(window).resize(function () {
    togglemenu();
    menuhrres();
    if ($("body").hasClass("layout-6") || $("body").hasClass("layout-7")) {
        togglemenulayout();
    }
});
function menuhrres() {
    var a = $(window)[0].innerWidth;
    if (a < 992) {
        setTimeout(function () {
            $(".sidenav-horizontal-wrapper").addClass("sidenav-horizontal-wrapper-dis").removeClass("sidenav-horizontal-wrapper");
            $(".theme-horizontal").addClass("theme-horizontal-dis").removeClass("theme-horizontal");
        }, 400);
    } else {
        setTimeout(function () {
            $(".sidenav-horizontal-wrapper-dis").addClass("sidenav-horizontal-wrapper").removeClass("sidenav-horizontal-wrapper-dis");
            $(".theme-horizontal-dis").addClass("theme-horizontal").removeClass("theme-horizontal-dis");
        }, 400);
    }
    setTimeout(function () {
        if ($(".pcoded-navbar").hasClass("theme-horizontal-dis")) {
            $(".sidenav-horizontal-wrapper-dis").css({ height: "100%", position: "relative" });
            if ($(".sidenav-horizontal-wrapper-dis")[0]) {
                var b = new PerfectScrollbar(".sidenav-horizontal-wrapper-dis", { wheelSpeed: 0.5, swipeEasing: 0, suppressScrollX: !0, wheelPropagation: 1, minScrollbarLength: 40 });
            }
        }
    }, 1000);
}
var ost = 0;
$(window).scroll(function () {
    var b = $(window)[0].innerWidth;
    if (b >= 768) {
        var a = $(this).scrollTop();
        if (a == 400) {
            $(".theme-horizontal").addClass("top-nav-collapse");
        } else {
            if (a > ost && 400 < ost) {
                $(".theme-horizontal").addClass("top-nav-collapse").removeClass("default");
            } else {
                $(".theme-horizontal").addClass("default").removeClass("top-nav-collapse");
            }
        }
        ost = a;
    }
});
function togglemenu() {
    var a = $(window)[0].innerWidth;
    if ($(".pcoded-navbar").hasClass("theme-horizontal") == false) {
        if (a <= 1200 && a >= 992) {
            $(".pcoded-navbar:not(.theme-horizontal)").addClass("navbar-collapsed");
        }
        if (a < 992) {
            $(".pcoded-navbar:not(.theme-horizontal)").removeClass("navbar-collapsed");
        }
    }
}
function toggleFullScreen() {
    var b = $(window).height() - 10;
    if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else {
                if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            }
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else {
            if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else {
                if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        }
    }
    $(".full-screen > i").toggleClass("icon-maximize");
    $(".full-screen > i").toggleClass("icon-minimize");
}
$.fn.pcodedmenu = function (a) {
    var b = this.attr("id");
    var c = { themelayout: "vertical", MenuTrigger: "click", SubMenuTrigger: "click" };
    var a = $.extend({}, c, a);
    var d = {
        PcodedMenuInit: function () {
            d.HandleMenuTrigger();
            d.HandleSubMenuTrigger();
            d.HandleOffset();
        },
        HandleSubMenuTrigger: function () {
            var g = $(window);
            var e = g.width();
            if ($(".pcoded-navbar").hasClass("theme-horizontal") == true || $(".pcoded-navbar").hasClass("theme-horizontal-dis") == true) {
                if ((e >= 992 && $("body").hasClass("layout-6")) || (e >= 992 && $("body").hasClass("layout-7"))) {
                    var f = $("body[class*='layout-6'] .theme-horizontal .pcoded-inner-navbar .pcoded-submenu > li.pcoded-hasmenu, body[class*='layout-7'] .theme-horizontal .pcoded-inner-navbar .pcoded-submenu > li.pcoded-hasmenu");
                    f.off("click")
                        .off("mouseenter mouseleave")
                        .hover(
                            function () {
                                $(this).addClass("pcoded-trigger").addClass("active");
                            },
                            function () {
                                $(this).removeClass("pcoded-trigger").removeClass("active");
                            }
                        );
                } else {
                    if ($("body").hasClass("layout-6") || $("body").hasClass("layout-7")) {
                        if ($(".pcoded-navbar").hasClass("theme-horizontal-dis")) {
                            var f = $(".pcoded-navbar.theme-horizontal-dis .pcoded-inner-navbar .pcoded-submenu > li.pcoded-hasmenu");
                            f.off("click")
                                .off("mouseenter mouseleave")
                                .hover(
                                    function () {
                                        $(this).addClass("pcoded-trigger").addClass("active");
                                    },
                                    function () {
                                        $(this).removeClass("pcoded-trigger").removeClass("active");
                                    }
                                );
                        }
                        if (!$(".pcoded-navbar").hasClass("theme-horizontal-dis")) {
                            var f = $(".pcoded-navbar:not(.theme-horizontal-dis) .pcoded-inner-navbar .pcoded-submenu > li > .pcoded-submenu > li");
                            f.off("mouseenter mouseleave")
                                .off("click")
                                .on("click", function () {
                                    var h = $(this).closest(".pcoded-submenu").length;
                                    if (h === 0) {
                                        if ($(this).hasClass("pcoded-trigger")) {
                                            $(this).removeClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideUp();
                                        } else {
                                            $(".pcoded-submenu > li > .pcoded-submenu > li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                            $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                            $(this).addClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideDown();
                                        }
                                    } else {
                                        if ($(this).hasClass("pcoded-trigger")) {
                                            $(this).removeClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideUp();
                                        } else {
                                            $(".pcoded-submenu > li > .pcoded-submenu > li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                            $(this).closest(".pcoded-submenu").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                            $(this).addClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideDown();
                                        }
                                    }
                                });
                            $(".pcoded-inner-navbar .pcoded-submenu > li > .pcoded-submenu > li").on("click", function (h) {
                                h.stopPropagation();
                                var i = $(this).closest(".pcoded-submenu").length;
                                if (i === 0) {
                                    if ($(this).hasClass("pcoded-trigger")) {
                                        $(this).removeClass("pcoded-trigger");
                                        $(this).children(".pcoded-submenu").slideUp();
                                    } else {
                                        $(".pcoded-hasmenu li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                        $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                        $(this).addClass("pcoded-trigger");
                                        $(this).children(".pcoded-submenu").slideDown();
                                    }
                                } else {
                                    if ($(this).hasClass("pcoded-trigger")) {
                                        $(this).removeClass("pcoded-trigger");
                                        $(this).children(".pcoded-submenu").slideUp();
                                    } else {
                                        $(".pcoded-hasmenu li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                        $(this).closest(".pcoded-submenu").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                        $(this).addClass("pcoded-trigger");
                                        $(this).children(".pcoded-submenu").slideDown();
                                    }
                                }
                            });
                        }
                    } else {
                        if (e >= 992) {
                            var f = $(".pcoded-navbar.theme-horizontal .pcoded-inner-navbar .pcoded-submenu > li.pcoded-hasmenu");
                            f.off("click")
                                .off("mouseenter mouseleave")
                                .hover(
                                    function () {
                                        $(this).addClass("pcoded-trigger").addClass("active");
                                    },
                                    function () {
                                        $(this).removeClass("pcoded-trigger").removeClass("active");
                                    }
                                );
                        } else {
                            var f = $(".pcoded-navbar.theme-horizontal-dis .pcoded-inner-navbar .pcoded-submenu > li > .pcoded-submenu > li");
                            f.off("mouseenter mouseleave")
                                .off("click")
                                .on("click", function () {
                                    var h = $(this).closest(".pcoded-submenu").length;
                                    if (h === 0) {
                                        if ($(this).hasClass("pcoded-trigger")) {
                                            $(this).removeClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideUp();
                                        } else {
                                            $(".pcoded-submenu > li > .pcoded-submenu > li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                            $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                            $(this).addClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideDown();
                                        }
                                    } else {
                                        if ($(this).hasClass("pcoded-trigger")) {
                                            $(this).removeClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideUp();
                                        } else {
                                            $(".pcoded-submenu > li > .pcoded-submenu > li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                            $(this).closest(".pcoded-submenu").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                            $(this).addClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideDown();
                                        }
                                    }
                                });
                        }
                    }
                }
            }
            switch (a.SubMenuTrigger) {
                case "click":
                    $(".pcoded-navbar .pcoded-hasmenu").removeClass("is-hover");
                    $(".pcoded-inner-navbar .pcoded-submenu > li > .pcoded-submenu > li").on("click", function (h) {
                        h.stopPropagation();
                        var i = $(this).closest(".pcoded-submenu").length;
                        if (i === 0) {
                            if ($(this).hasClass("pcoded-trigger")) {
                                $(this).removeClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideUp();
                            } else {
                                $(".pcoded-submenu > li > .pcoded-submenu > li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                $(this).addClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideDown();
                            }
                        } else {
                            if ($(this).hasClass("pcoded-trigger")) {
                                $(this).removeClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideUp();
                            } else {
                                $(".pcoded-submenu > li > .pcoded-submenu > li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                $(this).closest(".pcoded-submenu").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                $(this).addClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideDown();
                            }
                        }
                    });
                    $(".pcoded-submenu > li").on("click", function (h) {
                        h.stopPropagation();
                        var i = $(this).closest(".pcoded-submenu").length;
                        if (i === 0) {
                            if ($(this).hasClass("pcoded-trigger")) {
                                $(this).removeClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideUp();
                            } else {
                                $(".pcoded-hasmenu li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                $(this).addClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideDown();
                            }
                        } else {
                            if ($(this).hasClass("pcoded-trigger")) {
                                $(this).removeClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideUp();
                            } else {
                                $(".pcoded-hasmenu li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                $(this).closest(".pcoded-submenu").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                $(this).addClass("pcoded-trigger");
                                $(this).children(".pcoded-submenu").slideDown();
                            }
                        }
                    });
                    break;
            }
        },
        HandleMenuTrigger: function () {
            var g = $(window);
            var e = g.width();
            if (e >= 992) {
                if ($(".pcoded-navbar").hasClass("theme-horizontal") == true) {
                    if ((e >= 768 && $("body").hasClass("layout-6")) || (e >= 768 && $("body").hasClass("layout-7"))) {
                        var f = $("body[class*='layout-6'] .theme-horizontal .pcoded-inner-navbar > li,body[class*='layout-7'] .theme-horizontal .pcoded-inner-navbar > li ");
                        f.off("click")
                            .off("mouseenter mouseleave")
                            .hover(
                                function () {
                                    $(this).addClass("pcoded-trigger").addClass("active");
                                    if ($(".pcoded-submenu", this).length) {
                                        var p = $(".pcoded-submenu:first", this);
                                        var o = p.offset();
                                        var i = o.left;
                                        var h = p.width();
                                        var n = $(window).height();
                                        var k = $(window).width();
                                        var m = i + h <= k;
                                        if (!m) {
                                            var j = $(".sidenav-inner").attr("style");
                                            $(".sidenav-inner").css({ "margin-left": parseInt(j.slice(12, j.length - 3)) - 80 });
                                            $(".sidenav-horizontal-prev").removeClass("disabled");
                                        } else {
                                            $(this).removeClass("edge");
                                        }
                                    }
                                },
                                function () {
                                    $(this).removeClass("pcoded-trigger").removeClass("active");
                                }
                            );
                    } else {
                        if ($("body").hasClass("layout-6") || $("body").hasClass("layout-7")) {
                            if ($(".pcoded-navbar").hasClass("theme-horizontal-dis")) {
                                var f = $(".pcoded-navbar.theme-horizontal-dis .pcoded-inner-navbar > li");
                                f.off("click")
                                    .off("mouseenter mouseleave")
                                    .hover(
                                        function () {
                                            $(this).addClass("pcoded-trigger").addClass("active");
                                            if ($(".pcoded-submenu", this).length) {
                                                var p = $(".pcoded-submenu:first", this);
                                                var o = p.offset();
                                                var i = o.left;
                                                var h = p.width();
                                                var n = $(window).height();
                                                var k = $(window).width();
                                                var m = i + h <= k;
                                                if (!m) {
                                                    var j = $(".sidenav-inner").attr("style");
                                                    $(".sidenav-inner").css({ "margin-left": parseInt(j.slice(12, j.length - 3)) - 80 });
                                                    $(".sidenav-horizontal-prev").removeClass("disabled");
                                                } else {
                                                    $(this).removeClass("edge");
                                                }
                                            }
                                        },
                                        function () {
                                            $(this).removeClass("pcoded-trigger").removeClass("active");
                                        }
                                    );
                            }
                            if (!$(".pcoded-navbar").hasClass("theme-horizontal-dis")) {
                                var f = $(".pcoded-navbar:not(.theme-horizontal-dis) .pcoded-inner-navbar > li");
                                f.off("mouseenter mouseleave")
                                    .off("click")
                                    .on("click", function () {
                                        if ($(this).hasClass("pcoded-trigger")) {
                                            $(this).removeClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideUp();
                                        } else {
                                            $("li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                                            $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                                            $(this).addClass("pcoded-trigger");
                                            $(this).children(".pcoded-submenu").slideDown();
                                        }
                                    });
                            }
                        } else {
                            var f = $(".theme-horizontal .pcoded-inner-navbar > li");
                            f.off("click")
                                .off("mouseenter mouseleave")
                                .hover(
                                    function () {
                                        $(this).addClass("pcoded-trigger").addClass("active");
                                        if ($(".pcoded-submenu", this).length) {
                                            var p = $(".pcoded-submenu:first", this);
                                            var o = p.offset();
                                            var i = o.left;
                                            var h = p.width();
                                            var n = $(window).height();
                                            var k = $(window).width();
                                            var m = i + h <= k;
                                            if (!m) {
                                                var j = $(".sidenav-inner").attr("style");
                                                $(".sidenav-inner").css({ "margin-left": parseInt(j.slice(12, j.length - 3)) - 80 });
                                                $(".sidenav-horizontal-prev").removeClass("disabled");
                                            } else {
                                                $(this).removeClass("edge");
                                            }
                                        }
                                    },
                                    function () {
                                        $(this).removeClass("pcoded-trigger").removeClass("active");
                                    }
                                );
                        }
                    }
                }
            } else {
                var f = $(".pcoded-navbar.theme-horizontal-dis .pcoded-inner-navbar > li");
                f.off("mouseenter mouseleave")
                    .off("click")
                    .on("click", function () {
                        if ($(this).hasClass("pcoded-trigger")) {
                            $(this).removeClass("pcoded-trigger");
                            $(this).children(".pcoded-submenu").slideUp();
                        } else {
                            $("li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                            $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                            $(this).addClass("pcoded-trigger");
                            $(this).children(".pcoded-submenu").slideDown();
                        }
                    });
            }
            switch (a.MenuTrigger) {
                case "click":
                    $(".pcoded-navbar").removeClass("is-hover");
                    $(".pcoded-inner-navbar > li:not(.pcoded-menu-caption) ").on("click", function () {
                        if ($(this).hasClass("pcoded-trigger")) {
                            $(this).removeClass("pcoded-trigger");
                            $(this).children(".pcoded-submenu").slideUp();
                        } else {
                            $("li.pcoded-trigger").children(".pcoded-submenu").slideUp();
                            $(this).closest(".pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
                            $(this).addClass("pcoded-trigger");
                            $(this).children(".pcoded-submenu").slideDown();
                        }
                    });
                    break;
            }
        },
        HandleOffset: function () {
            switch (a.themelayout) {
                case "horizontal":
                    var e = a.SubMenuTrigger;
                    if (e === "hover") {
                        $("li.pcoded-hasmenu").on("mouseenter mouseleave", function (k) {
                            if ($(".pcoded-submenu", this).length) {
                                var n = $(".pcoded-submenu:first", this);
                                var m = n.offset();
                                var g = m.left;
                                var f = n.width();
                                var j = $(window).height();
                                var h = $(window).width();
                                var i = g + f <= h;
                                if (!i) {
                                    $(this).addClass("edge");
                                } else {
                                    $(this).removeClass("edge");
                                }
                            }
                        });
                    } else {
                        $("li.pcoded-hasmenu").on("click", function (k) {
                            k.preventDefault();
                            if ($(".pcoded-submenu", this).length) {
                                var n = $(".pcoded-submenu:first", this);
                                var m = n.offset();
                                var g = m.left;
                                var f = n.width();
                                var j = $(window).height();
                                var h = $(window).width();
                                var i = g + f <= h;
                                if (!i) {
                                    $(this).toggleClass("edge");
                                }
                            }
                        });
                    }
                    break;
                default:
            }
        },
    };
    d.PcodedMenuInit();
};
$("#pcoded").pcodedmenu({ MenuTrigger: "click", SubMenuTrigger: "click" });
$("#mobile-collapse,#mobile-collapse1").click(function (b) {
    var a = $(window)[0].innerWidth;
    if (a < 992) {
        $(".pcoded-navbar").toggleClass("mob-open");
        b.stopPropagation();
    }
});
$(window).ready(function () {
    var a = $(window)[0].innerWidth;
    $(".pcoded-navbar").on("click tap", function (b) {
        b.stopPropagation();
    });
    $(".pcoded-main-container,.pcoded-header").on("click", function () {
        if (a < 992) {
            if ($(".pcoded-navbar").hasClass("mob-open") == true) {
                $(".pcoded-navbar").removeClass("mob-open");
                $("#mobile-collapse,#mobile-collapse1").removeClass("on");
            }
        }
    });
});
$(".pcoded-navbar .close").on("click", function () {
    var a = $(this);
    a.parents(".card").fadeOut("slow").remove();
});
$(".pcoded-navbar .pcoded-inner-navbar a").each(function () {
    var b = window.location.href.split(/[?#]/)[0];
    if (!$("body").hasClass("layout-14")) {
        if (this.href == b && $(this).attr("href") != "") {
            $(this).parent("li").addClass("active");
            if (!$(".pcoded-navbar").hasClass("theme-horizontal")) {
                $(this).parent("li").parent().parent(".pcoded-hasmenu").addClass("active").addClass("pcoded-trigger");
                $(this).parent("li").parent().parent(".pcoded-hasmenu").parent().parent(".pcoded-hasmenu").addClass("active").addClass("pcoded-trigger");
            }
            if ($("body").hasClass("layout-7") || $("body").hasClass("layout-6")) {
                $(this).parent("li").parent().parent(".pcoded-hasmenu").addClass("active").addClass("pcoded-trigger");
                $(this).parent("li").parent().parent(".pcoded-hasmenu").parent().parent(".pcoded-hasmenu").addClass("active").addClass("pcoded-trigger");
                $(".theme-horizontal .pcoded-inner-navbar").find("li.pcoded-trigger").removeClass("pcoded-trigger");
            }
            $(this).parent("li").parent().parent(".sidelink").addClass("active");
            $(this).parent("li").parent().parent().parent().parent(".sidelink").addClass("active");
            $(this).parent("li").parent().parent().parent().parent().parent().parent(".sidelink").addClass("active");
            if ($("body").hasClass("layout-1") || $("body").hasClass("layout-9")) {
                var a = $(".sidelink.active").attr("class");
                a = a.replace("sidelink", "");
                a = a.replace("active", "");
                $(".sidemenu  .nav-link[data-cont=" + a.trim() + "]")
                    .parent()
                    .addClass("active");
            }
        }
    }
});
$(window).scroll(function () {
    if (!$(".pcoded-header").hasClass("headerpos-fixed")) {
        if ($(this).scrollTop() > 60) {
            $(".pcoded-navbar.menupos-fixed").css("position", "fixed");
            $(".pcoded-navbar.menupos-fixed").css("transition", "none");
            $(".pcoded-navbar.menupos-fixed").css("margin-top", "0px");
        } else {
            $(".pcoded-navbar.menupos-fixed").removeAttr("style");
            $(".pcoded-navbar.menupos-fixed").css("position", "absolute");
            $(".pcoded-navbar.menupos-fixed").css("margin-top", "56px");
        }
    }
    if ($("body").hasClass("box-layout")) {
        if ($(this).scrollTop() > 60) {
            $(".pcoded-navbar").css("position", "fixed");
            $(".pcoded-navbar").css("transition", "none");
            $(".pcoded-navbar").css("margin-top", "0px");
            $(".pcoded-navbar").css("border-radius", "0px");
        } else {
            $(".pcoded-navbar").removeAttr("style");
            $(".pcoded-navbar").css("position", "absolute");
            $(".pcoded-navbar").css("margin-top", "56px");
        }
    }
});
$("#more-details").on("click", function () {
    $("#nav-user-link").slideToggle();
});
