<?php
/*
 * @Theme Name:NextStack
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
<nav class="navbar" role="navigation">
  <div class="navbar-content">
    <ul class="ul-list list-inline list-unstyled">
      <li class="hidden-xs">
        <a class="sidebar-toggle" data-toggle="sidebar" href="#">
          <!--<i class="fa fa-bars"></i>-->
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-fold-icon">
                <path d="M6 9 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M19 19 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M19 29 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M11 19 L6 24 L11 29" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M6 39 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-unfold-icon">
                <path d="M6 9 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M19 19 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M19 29 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M6 19 L11 24 L6 29" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M6 39 H42" stroke="#555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
      </li>
    </ul>
    <ul class="ul-list list-inline list-unstyled">
      <!-- 天气 -->
      <li>
        <div id="he-plugin-simple"></div>
        <script>
          WIDGET = {
            CONFIG: {
              "modules": "12034",
              "background": 5,
              "tmpColor": "aaa",
              "tmpSize": 16,
              "cityColor": "aaa",
              "citySize": 16,
              "aqiSize": 16,
              "weatherIconSize": 24,
              "alertIconSize": 18,
              "padding": "30px 10px 30px 10px",
              "shadow": "1",
              "language": "auto",
              "borderRadius": 5,
              "fixed": "false",
              "vertical": "middle",
              "horizontal": "left",
              "key": "a922adf8928b4ac1ae7a31ae7375e191"
            }
          }
        </script>
        <script src="https://widget.heweather.net/simple/static/js/he-simple-common.js?v=1.1"></script>
      </li>
      <!-- 天气 end -->
      <li class="hidden-sm hidden-xs">
        <a href="https://github.com/lyove" target="_blank">
          <i class="fa fa-github"></i> GitHub
        </a>
      </li>
    </ul>
  </div>
</nav>
<div class="navbar-fixed-blank"></div>