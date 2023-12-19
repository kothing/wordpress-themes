
var public_vars = public_vars || {};
jQuery.extend(public_vars, {
  breakpoints: {
    largescreen: [991, -1],
    tabletscreen: [768, 990],
    devicescreen: [420, 767],
    sdevicescreen: [0, 419],
  },
  lastBreakpoint: null,
});

(function ($, window, undefined) {
  "use strict";
  $(document).ready(function () {
    // Main Vars
    public_vars.$body = $("body");
    public_vars.$pageContainer = public_vars.$body.find(".page-container");
    public_vars.$sidebar = public_vars.$pageContainer.find(".sidebar");
    public_vars.$mainMenu = public_vars.$sidebar.find(".main-menu");
    public_vars.$sideMenuPopover = [];
    public_vars.$horizontalNavbar = public_vars.$body.find(".navbar.horizontal-menu");
    public_vars.$mainContent = public_vars.$pageContainer.find(".main-content");
    public_vars.$mainFooter = public_vars.$body.find("footer.main-footer");
    public_vars.$userInfoMenuHor = public_vars.$body.find(".navbar.horizontal-menu");
    public_vars.$userInfoMenu = public_vars.$body.find("nav.navbar");
    public_vars.$settingsPane = public_vars.$body.find(".settings-pane");
    public_vars.$settingsPaneIn = public_vars.$settingsPane.find(".settings-pane-inner");
    public_vars.wheelPropagation = true; // used in Main menu (sidebar)
    public_vars.$pageLoadingOverlay = public_vars.$body.find(".page-loading-overlay");
    public_vars.defaultColorsPalette = [
      "#68b828",
      "#7c38bc",
      "#0e62c7",
      "#fcd036",
      "#4fcdfc",
      "#00b19d",
      "#ff6264",
      "#f7aa47",
    ];
    
    // Setup Sidebar Menu
    setup_sidebar_menu();
    
    // Sidebar Toggle
    $('a[data-toggle="sidebar"]').each(function (i, el) {
      $(el).on("click", function (ev) {
        ev.preventDefault();
        if (public_vars.$sidebar.hasClass("collapsed")) {
          public_vars.$sidebar.removeClass("collapsed");
          public_vars.$sidebar.addClass("uncollapsed");
          public_vars.$body.removeClass("sidebar-collapsed");
          public_vars.$sideMenuPopover.forEach(function(pop) {
            pop.updateConfig({
              disabled: true
            });
          });
        } else {
          public_vars.$body.addClass("sidebar-collapsed");
          public_vars.$sidebar.removeClass("uncollapsed");
          public_vars.$sidebar.addClass("collapsed");
          public_vars.$sideMenuPopover.forEach(function(pop) {
            pop.updateConfig({
              disabled: isMobileScreen() || jQuery(window).width() <= 768
            });
          });
        }
        $(window).trigger("xenon.resize");
      });
    });
    
    // Mobile Menu Trigger
    $('a[data-toggle="mobile-menu"]').on("click", function (ev) {
      ev.preventDefault();
      public_vars.$mainMenu.toggleClass("mobile-is-visible");
      if ($(".main-menu").hasClass("mobile-is-visible") === true) {
        public_vars.$body.removeClass("sidebar-collapsed");
        public_vars.$sidebar.removeClass("collapsed");
        public_vars.$sidebar.addClass("uncollapsed");
        $(".sidebar-inner").css("max-height", window.innerHeight);
        $(".sidebar-inner .main-menu").css("max-height", window.innerHeight - 60);
      } else {
        public_vars.$body.addClass("sidebar-collapsed");
        public_vars.$sidebar.removeClass("uncollapsed");
        public_vars.$sidebar.addClass("collapsed");
        $(".sidebar-inner").removeAttr("style");
        $(".sidebar-inner .main-menu").removeAttr("style");
      }
    });
    
    // Tooltips
    $('[data-toggle="tooltip"]').each(function (i, el) {
      var $this = $(el),
        placement = attrDefault($this, "placement", "top"),
        trigger = attrDefault($this, "trigger", "hover"),
        tooltip_class = $this.get(0).className.match(/(tooltip-[a-z0-9]+)/i);
      $this.tooltip({
        placement: placement,
        trigger: trigger,
      });
      if (tooltip_class) {
        $this.removeClass(tooltip_class[1]);
        $this.on("show.bs.tooltip", function (ev) {
          setTimeout(function () {
            var $tooltip = $this.next();
            $tooltip.addClass(tooltip_class[1]);
          }, 0);
        });
      }
    });
    
    // Sticky Footer
    if (public_vars.$mainFooter.hasClass("sticky")) {
      stickFooterToBottom();
      $(window).on("xenon.resized", stickFooterToBottom);
    }
    
    // Go to top links
    $("body").on("click", 'a[rel="go-top"]', function (ev) {
      ev.preventDefault();
      var obj = { pos: $(window).scrollTop() };
      TweenLite.to(obj, 0.3, {
        pos: 0,
        ease: Power4.easeOut,
        onUpdate: function () {
          $(window).scrollTop(obj.pos);
        },
      });
    });
    
    // Setup Sidebar popover
    setup_sidebar_menu_popover()
  });
  
  // Enable/Disable Resizable Event
  var wid = 0;
  $(window).resize(function () {
    clearTimeout(wid);
    wid = setTimeout(trigger_resizable, 200);
  });
})(jQuery, window);

function isMobileScreen() {
  return /Android|iPhone|iPad|iPod|BlackBerry|webOS|Windows Phone|SymbianOS|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

/* Main Function that will be called each time when the screen breakpoint changes */
function resizable(breakpoint) {
  var sb_with_animation;
  // Large Screen Specific Script
  if (isThisScreen("largescreen")) {
    //
  }
  
  // Tablet Screen Specific Script
  if (isThisScreen("tabletscreen")) {
    //
  }
  
  // Device screen
  if (isThisScreen("devicescreen")) {
    //
  }
  
  // Device screen
  if (isThisScreen("sdevicescreen")) {
    //
  }
  
  // Larger or tablet screen
  if (is_md_xl()) {
    public_vars.$body.removeClass("sidebar-collapsed");
    public_vars.$sidebar.removeClass("collapsed");
    public_vars.$sidebar.addClass("uncollapsed");
  }
  
  // Device or sdevice screen
  if (is_xs()) {
    public_vars.$body.addClass("sidebar-collapsed");
    public_vars.$sidebar.removeClass("uncollapsed");
    public_vars.$sidebar.addClass("collapsed");
  }

  // Trigger Event
  jQuery(window).trigger("xenon.resize");
}

// Get current breakpoint
function get_current_breakpoint() {
  var width = jQuery(window).width();
  var breakpoints = public_vars.breakpoints;
  for (var breakpont_label in breakpoints) {
    var bp_arr = breakpoints[breakpont_label],
      min = bp_arr[0],
      max = bp_arr[1];
    if (max == -1) {
      max = width;
    }
    if (min <= width && max >= width) {
      return breakpont_label;
    }
  }
  return null;
}

// Check current screen breakpoint
function isThisScreen(screen_label) {
  return get_current_breakpoint() == screen_label;
}

// Is xs device
function is_xs() {
  return isThisScreen("devicescreen") || isThisScreen("sdevicescreen");
}

// Is md or xl
function is_md_xl() {
  return isThisScreen("tabletscreen") || isThisScreen("largescreen");
}

// Trigger Resizable Function
function trigger_resizable() {
  if (public_vars.lastBreakpoint != get_current_breakpoint()) {
    public_vars.lastBreakpoint = get_current_breakpoint();
    resizable(public_vars.lastBreakpoint);
  }
  // Trigger Event (Repeated)
  jQuery(window).trigger("xenon.resized");
}

// Sideber Menu Setup function
var sm_duration = 0.2;
var sm_transition_delay = 150;

function setup_sidebar_menu() {
  if (public_vars.$sidebar.length) {
    var $items_with_subs = public_vars.$sidebar.find("li:has(> ul)");
    var toggle_sidebar = public_vars.$sidebar.hasClass("toggle-sidebar");
    $items_with_subs.filter(".active").addClass("expanded");
    
    
    // Collapse sidebar when the window is tablet screen
    if ( isThisScreen("devicescreen") || isThisScreen("sdevicescreen") ) {
      public_vars.$body.addClass("sidebar-collapsed");
      public_vars.$sidebar.removeClass("uncollapsed");
      public_vars.$sidebar.addClass("collapsed");
    }

    $(window).on("resize", function () {
      let page_width = jQuery(window).width();

      if (isThisScreen("largescreen") || isThisScreen("tabletscreen")) {
        public_vars.$body.removeClass("sidebar-collapsed");
        public_vars.$sidebar.removeClass("collapsed");
        public_vars.$sidebar.addClass("uncollapsed");
      } else {
        public_vars.$body.addClass("sidebar-collapsed");
        public_vars.$sidebar.removeClass("uncollapsed");
        public_vars.$sidebar.addClass("collapsed");
        
        $(".main-menu").removeClass("mobile-is-visible");
        $(".sidebar-inner").removeAttr("style");
        $(".sidebar-inner .main-menu").removeAttr("style");
      }
      
      let popDisabled = !public_vars.$sidebar.hasClass("collapsed") || isMobileScreen() || page_width <= 768;
      public_vars.$sideMenuPopover.forEach(function(pop) {
        pop.updateConfig({
          disabled: popDisabled
        });
      });
    });
    
    $items_with_subs.each(function (i, el) {
      var $li = jQuery(el);
      var $a = $li.children("a");
      var $sub = $li.children("ul");
      $li.addClass("has-sub");
      $a.on("click", function (ev) {
        ev.preventDefault();
        if (toggle_sidebar) {
          sidebar_menu_close_items_siblings($li);
        }
        if ($li.hasClass("expanded") || $li.hasClass("opened")) {
          sidebar_menu_item_collapse($li, $sub);
        } else {
          sidebar_menu_item_expand($li, $sub);
        }
      });
    });
  }
}

function sidebar_menu_item_expand($li, $sub) {
  if (
    $li.data("is-busy") ||
    ($li.parent(".main-menu").length && public_vars.$sidebar.hasClass("collapsed"))
  ) {
    return;
  }
  $li.addClass("expanded").data("is-busy", true);
  $sub.show();
  var $sub_items = $sub.children(),
    sub_height = $sub.outerHeight(),
    win_y = jQuery(window).height(),
    total_height = $li.outerHeight(),
    current_y = public_vars.$sidebar.scrollTop(),
    item_max_y = $li.position().top + current_y,
    fit_to_viewpport = public_vars.$sidebar.hasClass("fit-in-viewport");
  $sub_items.addClass("is-hidden");
  $sub.height(0);
  TweenMax.to($sub, sm_duration, {
    css: { height: sub_height },
    onComplete: function () {
      $sub.height("");
    },
  });
  var interval_1 = $li.data("sub_i_1"),
    interval_2 = $li.data("sub_i_2");
  window.clearTimeout(interval_1);
  interval_1 = setTimeout(function () {
    $sub_items.each(function (i, el) {
      var $sub_item = jQuery(el);
      $sub_item.addClass("is-shown");
    });
    var finish_on = sm_transition_delay * $sub_items.length,
      t_duration = parseFloat($sub_items.eq(0).css("transition-duration")),
      t_delay = parseFloat($sub_items.last().css("transition-delay"));
    if (t_duration && t_delay) {
      finish_on = (t_duration + t_delay) * 1000;
    }
    // In the end
    window.clearTimeout(interval_2);
    interval_2 = setTimeout(function () {
      $sub_items.removeClass("is-hidden is-shown");
    }, finish_on);
    $li.data("is-busy", false);
  }, 0);
  $li.data("sub_i_1", interval_1), $li.data("sub_i_2", interval_2);
}

function sidebar_menu_item_collapse($li, $sub) {
  if ($li.data("is-busy")) {
    return;
  }
  var $sub_items = $sub.children();
  $li.removeClass("expanded").data("is-busy", true);
  $sub_items.addClass("hidden-item");
  TweenMax.to($sub, sm_duration, {
    css: { height: 0 },
    onComplete: function () {
      $li.data("is-busy", false).removeClass("opened");
      $sub.attr("style", "").hide();
      $sub_items.removeClass("hidden-item");
      $li
        .find("li.expanded ul")
        .attr("style", "")
        .hide()
        .parent()
        .removeClass("expanded");
    },
  });
}

function sidebar_menu_close_items_siblings($li) {
  $li
    .siblings()
    .not($li)
    .filter(".expanded, .opened")
    .each(function (i, el) {
      var $_li = jQuery(el),
        $_sub = $_li.children("ul");
      sidebar_menu_item_collapse($_li, $_sub);
    });
}

function setup_sidebar_menu_popover() {
  var triggerItems = document.querySelectorAll(".sidebar .main-menu .menu-item");
  triggerItems.forEach(function(item) {
    var pop = new NextPopover.default({
      trigger: item,
      content: item.querySelector(".cat-name").innerText,
      appendTo: document.body,
      wrapperClass: "menu-item-popover",
      animationClass: "fade",
      placement: "right",
      emit: "hover",
      showArrow: true,
      openDelay: 20,
      closeDelay: 20,
      disabled: public_vars.$sidebar.hasClass("collapsed") ? false : true
    });
    public_vars.$sideMenuPopover.push(pop);
  });
}

function stickFooterToBottom() {
  public_vars.$mainFooter
    .add(public_vars.$mainContent)
    .add(public_vars.$sidebar)
    .attr("style", "");
  if (is_xs()) {
    return false;
  }
  if (public_vars.$mainFooter.hasClass("sticky")) {
    var win_height = jQuery(window).height(),
      footer_height = public_vars.$mainFooter.outerHeight(true),
      main_content_height =
        public_vars.$mainFooter.position().top + footer_height,
      main_content_height_only = main_content_height - footer_height,
      extra_height = public_vars.$horizontalNavbar.outerHeight();
    if (
      win_height >
      main_content_height - parseInt(public_vars.$mainFooter.css("marginTop"), 10)
    ) {
      public_vars.$mainFooter.css({
        marginTop: win_height - main_content_height - extra_height,
      });
    }
  }
}

// Element Attribute Helper
function attrDefault($el, data_var, default_val) {
  if (typeof $el.data(data_var) != "undefined") {
    return $el.data(data_var);
  }
  return default_val;
}
