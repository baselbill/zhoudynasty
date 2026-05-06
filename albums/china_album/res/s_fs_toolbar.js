/* USE WORDWRAP & MAXIMIZE THE WINDOW TO SEE THIS FILE
========================================
 SmartMenus v5.5 Frame Support Toolbar
========================================
 (c)2005 ET VADIKOM-VASIL DINKOV
========================================
*/


s_menusFrameName='Photos';
   // the name of the frame where the menus should appear

// ===
s_dE=document[document.compatMode=="CSS1Compat"?"documentElement":"body"];s_ua=navigator.userAgent.toLowerCase();if(window.event+""=="undefined")event=0;function s_rl(){if(!(document.all&&!window.innerWidth&&s_ua.indexOf("msie")!=-1)||s_ua.indexOf("mac")!=-1)return false;var o=document.body;while(o.parentNode){if(o.dir=="rtl"||o.style&&o.style.direction=="rtl")return true;o=o.parentNode};return false};function s_show(a,e){var f=parent.frames[s_menusFrameName];if(!f)return;if(typeof(f.s_AL)!="undefined")f.s_showFS(a,e)};function s_hide(){var f=parent.frames[s_menusFrameName];if(!f)return;if(typeof(f.s_AL)!="undefined")f.s_hide()}