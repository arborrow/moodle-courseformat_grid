// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Grid Format - A topics based format that uses a grid of user selectable images to popup a light box of the section.
 *
 * @package    course/format
 * @subpackage grid
 * @copyright  &copy; 2012 G J Barnard in respect to modifications of standard topics format.
 * @author     G J Barnard - gjbarnard at gmail dot com and {@link http://moodle.org/user/profile.php?id=442195}
 * @author     Based on code originally written by Paul Krix and Julian Ridden.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
 * @namespace
 */
M.format_grid = M.format_grid || {};
M.format_grid.shadebox = M.format_grid.shadebox || {};
M.format_grid.ourYUI;
M.format_grid.editing_on;
M.format_grid.update_capability;
M.format_grid.selected_topic;

M.format_grid.init = function(Y, the_editing_on, the_update_capability) {
    "use strict";
    this.ourYUI = Y;
    this.editing_on = the_editing_on;
    this.update_capability = the_update_capability;
    this.selected_topic = null;

    Y.delegate('click', this.icon_click, Y.config.doc, 'ul.gridicons a', this);

    var shadeboxtoggleone = Y.one("#shadebox_overlay");
    if (shadeboxtoggleone) {
        shadeboxtoggleone.on('click', this.shadebox.toggle_shadebox);
    }
    var shadeboxtoggletwo = Y.one("#shadebox_close");
    if (shadeboxtoggletwo) {
        shadeboxtoggletwo.on('click', this.shadebox.toggle_shadebox);
    }
};

M.format_grid.hide_sections = function () {
    // Have to hide the div's using javascript so they are visible if javascript is disabled.
    var grid_sections = getElementsByClassName(document.getElementById("middle-column"), "li", "grid_section");
    for(var i = 0; i < grid_sections.length; i++) {
        grid_sections[i].style.display = 'none';
    }
    // Remove href link from icon anchors so they don't compete with javascript onlick calls.
    var icon_links = getElementsByClassName(document.getElementById("iconContainer"), "a", "icon_link");
    for(var i = 0; i < icon_links.length; i++) {
        icon_links[i].href = "#";
    }
    document.getElementById("shadebox_close").style.display = "";

    M.format_grid.shadebox.initialize_shadebox();
    M.format_grid.shadebox.update_shadebox();
    window.onresize = function() {
        M.format_grid.shadebox.update_shadebox();
    }
}

M.format_grid.icon_click = function(e) {
    var iconIndex = parseInt(e.currentTarget.get('id').replace("gridsection-", ""));
    e.preventDefault();
    this.select_topic(iconIndex);
};


M.format_grid.select_topic = function(topic_no) {
    if ((this.editing_on == true) && (this.update_capability == true)) {
        document.getElementById("section-"+topic_no).style.display = "";
        window.scroll(0,document.getElementById("section-"+topic_no).offsetTop);
    } else {
        // Make the selected topic visible, scroll to it and hide all other topics.
        if(this.selected_topic != null) {
            document.getElementById("section-" + this.selected_topic).style.display = "none";
        }
        this.selected_topic = topic_no;

        document.getElementById("section-" + topic_no).style.display = "";
        // window.scroll(0,document.getElementById("section-"+topic_no).offsetTop);
        this.shadebox.toggle_shadebox();
    }
    return true;
}

/** Below is shadebox code **/
M.format_grid.shadebox.shadebox_open;

M.format_grid.shadebox.initialize_shadebox = function() {
    this.shadebox_open = false;
    this.hide_shadebox();

    document.getElementById('shadebox_overlay').style.display="";
    document.body.appendChild(document.getElementById('shadebox'));

    var content = document.getElementById('shadebox_content');
    content.style.position = 'absolute';
    content.style.width = '800px';
    content.style.top = '50px';
    content.style.left = '50%';
    content.style.marginLeft = '-400px';
    content.style.zIndex = '9000001';
}

M.format_grid.shadebox.toggle_shadebox = function() {
    if (M.format_grid.shadebox.shadebox_open) {
        M.format_grid.shadebox.hide_shadebox();
        M.format_grid.shadebox.shadebox_open = false;
        window.scrollTo(0, 0);
    } else {
        M.format_grid.shadebox.show_shadebox();
        M.format_grid.shadebox.shadebox_open = true;
    }
}

M.format_grid.shadebox.show_shadebox = function() {
    M.format_grid.shadebox.update_shadebox();
    document.getElementById("shadebox").style.display = "";
    M.format_grid.shadebox.update_shadebox();
}

M.format_grid.shadebox.hide_shadebox = function() {
    document.getElementById("shadebox").style.display = "none";
}

// Code from quirksmode.org.
// Author unknown.
M.format_grid.shadebox.get_page_size = function() {
    var xScroll, yScroll;
    if(window.innerHeight && window.scrollMaxY) {
        xScroll = document.body.scrollWidth;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if(document.body.scrollHeight > document.body.offsetHeight) { // All but Explorer Mac.
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else { // Explorer Mac ... also works in Explorer 6 strict and safari.
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }

    var windowWidth, windowHeight;
    if(self.innerHeight) { // All except Explorer.
        windowWidth = self.innerWidth;
        windowHeight = self.innerHeight;
    } else if(document.documentElement && document.documentElement.clientHeight) { // Explorer 6 strict mode.
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if(document.body) { //other Explorers
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }

    // For small pages with total height less than height of the viewport.
    if(yScroll < windowHeight) {
        pageHeight = windowHeight;
    } else {
        pageHeight = yScroll;
    }

    // For small pages with total width less than width of the viewport.
    if(xScroll < windowWidth) {
        pageWidth = windowWidth;
    } else {
        pageWidth = xScroll;
    }

    arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);
    return arrayPageSize;
}

M.format_grid.shadebox.update_shadebox = function() {
    // Make the overlay fullscreen (width happens automatically, so just update the height).
    var overlay = document.getElementById("shadebox_overlay");
    var pagesize = this.get_page_size();
    overlay.style.height = pagesize[1] + "px";
}
