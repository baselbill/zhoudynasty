// USE WORDWRAP AND MAXIMIZE THE WINDOW TO SEE THIS FILE
// v5

// === 1 === EXTRAS
s_hideTimeout=500;//1000=1 second
s_subShowTimeout=300;//if <=100 the menus will function like SM4.x
s_subMenuOffsetX=4;//pixels (if no subs, leave as you like)
s_subMenuOffsetY=1;
s_keepHighlighted=true;
s_autoSELECTED=false;//make the item linking to the current page SELECTED
s_autoSELECTEDItemsClickable=false;//look at IMPORTANT NOTES 1 in the Manual
s_autoSELECTEDTree=true;//look at IMPORTANT NOTES 1 in the Manual
s_autoSELECTEDTreeItemsClickable=true;//look at IMPORTANT NOTES 1 in the Manual
s_scrollingInterval=30;//scrolling for tall menus
s_rightToLeft=false;
s_hideSELECTsInIE=false;//look at IMPORTANT HOWTOS 7 in the Manual


// === 2 === Default TARGET for all the links
// for navigation to frame, calling functions or
// different target for any link look at
// IMPORTANT HOWTOS 1 NOTES in the Manual
s_target='newWindow';//(newWindow/self/top)


// === 3 === STYLESHEETS- you can define different arrays and then assign
// them to any menu you want with the s_add() function
s_CSSMain=[
menuBorderColorDOM ,	// BorderColorDOM ('top right bottom left' or 'all')
menuBorderColorNS4 ,	// BorderColorNS4
menuBorderWidth ,		// BorderWidth
menuBgColor ,	// BgColor
menuPadding ,		// Padding
menuItemBgColor ,	// ItemBgColor
menuItemBgColorOver ,	// ItemOverBgColor
menuItemFontColor ,	// ItemFontColor
menuItemFontColorOver ,	// ItemOverFontColor
menuItemFontFamily ,	// ItemFontFamily
menuItemFontSize ,		// ItemFontSize (css)
menuItemFontSizeNS4 ,		// ItemFontSize Netscape4 (look at KNOWN BUGS 3 in the Manual)
menuItemFontWeight ,		// ItemFontWeight (bold/normal)
menuItemTextAlign ,		// ItemTextAlign (left/center/right)
menuItemPadding ,		// ItemPadding
menuSeparatorSize,		// ItemSeparatorSize
menuSeparatorColor ,	// ItemSeparatorColor
menuItemIEfilter,		// IEfilter (look at Samples\IE4(5.5)Filters dirs)
true,				// UseSubImg
resourcesPath+'arrow.gif',	// SubImgSrc
resourcesPath+'arrowover.gif',	// OverSubImgSrc
7,				// SubImgWidth
7,				// SubImgHeight
5,				// SubImgTop px (from item top)
'#666666',			// SELECTED ItemBgColor (not used by PixPlayer)
'#FFFFFF',			// SELECTED ItemFontColor (not used by PixPlayer)
resourcesPath+'arrowover.gif',	// SELECTED SubImgSrc (not used by PixPlayer)
true,				// UseScrollingForTallMenus
resourcesPath+'scrolltop.gif',	// ScrollingImgTopSrc
resourcesPath+'scrollbottom.gif',// ScrollingImgBottomSrc
68,				// ScrollingImgWidth
12,				// ScrollingImgHeight
menuItemClass ,		// ItemClass (css)
menuItemClassOver ,		// ItemOverClass (css)
'',		// SELECTED ItemClass (css) (not used by PixPlayer)
menuItemBorderWidth ,		// ItemBorderWidth
menuItemBorderColor ,	// ItemBorderColor ('top right bottom left' or 'all')
menuItemBorderColor ,	// ItemBorderOverColor ('top right bottom left' or 'all')
'#005CA5',	// SELECTED ItemBorderColor ('top right bottom left' or 'all') (not used by PixPlayer)
menuSeparatorSpacing ,		// ItemSeparatorSpacing
menuSeparatorBgImage		// ItemSeparatorBgImage
];

// === 4 === MENU DEFINITIONS
s_add(
{
N:'mainmenu',	// NAME
LV:1,		// LEVEL (look at IMPORTANT NOTES 1 in the Manual)
MinW:150,	// MINIMAL WIDTH
T:1000,		// TOP (look at IMPORTANT HOWTOS 6 in the Manual)
L:5,		// LEFT (look at IMPORTANT HOWTOS 6 in the Manual)
P:false,	// menu is PERMANENT (you can only set true if this is LEVEL 1 menu)
S:s_CSSMain	// STYLE Array to use for this menu
},
[		// define items {U:'url',T:'text' ...} look at the Manual for details
{U:relRootPath+'index.html',T:'Main Gallery',Target:'top'}
]
);