/*
Template Name: Nonuxor
Author: <a href="http://www.os-templates.com/">OS Templates</a>
Author URI: http://www.os-templates.com/
Licence: Free to use under our free template licence terms
Licence URI: http://www.os-templates.com/template-terms
File: Mobile Menu JS

Thanks to:
"Convert a Menu to a Dropdown for Small Screens" from Chris Collier - http://css-tricks.com/convert-menu-to-dropdown/
"Submenu's with a dash" Daryn St. Pierre - http://jsfiddle.net/bloqhead/Kq43X/
*/

$('<form action="#"><select /></form>').appendTo("#mainav");
$("<option />", { selected:"selected", value:"", text:"MENU" }).appendTo("#mainav select");

$("#mainav a").each(function() {
    var e = $(this);
    var newitem = $('<option />', { value: e.attr('href') }).appendTo('#mainav select');
    var level = 1;

    if ($(e).parents("ul ul ul").length>=1) {
        level = 3;
    } else if ($(e).parents("ul ul").length>=1) {
        level = 2;
    }

    newitem.text('- '.repeat(level) + e.text());
    newitem.attr('scroll-to', e.attr('scroll-to'));
});

$("#mainav select").change(function() {
    if($(this).find("option:selected").val()!=="#") {
        window.location = $(this).find("option:selected").val()
    }

    //$('#mainav select :first-child').prop('selected', true);
});

