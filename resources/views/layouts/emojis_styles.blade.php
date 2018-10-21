<style>

span.emoji {
    display: -moz-inline-box;
    -moz-box-orient: vertical;
    display: inline-block;
    vertical-align: baseline;
    *vertical-align: auto;
    *zoom: 1;
    *display: inline;
    width: 1em;
    height: 1em;
    background-size: 1em;
    background-repeat: no-repeat;
    text-indent: -9999px;
}

span.emoji-sizer {
    line-height: 0.81em;
    font-size: 1em;
    margin: -2px 0;
}

span.emoji-outer {
    display: -moz-inline-box;
    display: inline-block;
    *display: inline;
    height: 1em;
    width: 1em;
}

span.emoji-inner {
    display: -moz-inline-box;
    display: inline-block;
    text-indent: -9999px;
    width: 100%;
    height: 100%;
    vertical-align: baseline;
    *vertical-align: auto;
    *zoom: 1;
}

img.emoji {
    width: 1em;
    height: 1em;
}

.emoji-wysiwyg-editor:empty:before {
    content: attr(placeholder);
    color: #9aa2ab;
}

.emoji-picker-container {
    position: relative;
}

.emoji-picker-icon {
    cursor: pointer;
    position: absolute;
    right: 10px;
    top: 5px;
    font-size: 20px;
    opacity: 0.7;
    z-index: 100;
    transition: none;
    color: black;
    -moz-user-select: none;
    -khtml-user-select: none;
    -webkit-user-select: none;
    -o-user-select: none;
    user-select: none;
}

.emoji-picker-icon.parent-has-scroll {
    right: 28px;
}

.emoji-picker-icon:hover {
    opacity: 1;
}

/* Emoji area */
.emoji-wysiwyg-editor:empty:before {
    content: attr(placeholder);
    color: #9aa2ab;
}

.emoji-wysiwyg-editor:active:before,
.emoji-wysiwyg-editor:focus:before {
    content: none;
}

.emoji-wysiwyg-editor {
    padding: 6px;
    padding-right: 35px;
    margin-bottom: 0px;
    min-height: 35px; /* 35 */
    height: 30px;
    max-height: 284px;
    overflow: auto;
    line-height: 17px;
    border: 1px solid #d2dbe3;
    border-radius: 2px;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    -webkit-user-select: text;
    word-wrap: break-word;
}

.emoji-wysiwyg-editor.parent-has-scroll {
     padding-right: 40px;
 }

.emoji-wysiwyg-editor.single-line-editor {
    min-height: 35px;
    height: inherit;
}

.emoji-wysiwyg-editor img {
    width: 20px;
    height: 20px;
    vertical-align: middle;
    margin: -3px 0 0 0;
}

.emoji-menu {
    position: absolute;
    right: 0;
    top: 0;
    z-index: 999;
    width: 225px;
    overflow: hidden;
    border: 1px #dfdfdf solid;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    overflow: hidden;
    -webkit-box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.1);
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.1);
}

.emoji-items-wrap1 {
    background: #ffffff;
    padding: 5px 2px 5px 5px;
}

.emoji-items-wrap1 .emoji-menu-tabs {
    width: 100%;
    margin-bottom: 8px;
    margin-top: 3px;
}

.emoji-items-wrap1 .emoji-menu-tabs td {
    text-align: center;
    color: white;
    line-height: 0;
}

.emoji-menu-tabs .emoji-menu-tab {
    display: inline-block;
    width: 24px;
    height: 29px;
    background: url('{{ asset("assets/img/emojis/IconsetSmiles.png") }}') no-repeat;
    background-size: 42px 350px;
    border: 0;
}

.is_1x .emoji-menu-tabs .emoji-menu-tab {
    background-image: url('{{ asset("assets/img/emojis/IconsetSmiles_1x.png") }}');
}

.emoji-menu-tabs .icon-recent { background-position: -9px -306px; }

.emoji-menu-tabs .icon-recent-selected { background-position: -9px -277px; }

.emoji-menu-tabs .icon-smile { background-position: -9px -34px; }

.emoji-menu-tabs .icon-smile-selected { background-position: -9px -5px; }

.emoji-menu-tabs .icon-flower { background-position: -9px -145px; }

.emoji-menu-tabs .icon-flower-selected { background-position: -9px -118px; }

.emoji-menu-tabs .icon-bell { background-position: -9px -89px; }

.emoji-menu-tabs .icon-bell-selected { background-position: -9px -61px; }

.emoji-menu-tabs .icon-car { background-position: -9px -196px; }

.emoji-menu-tabs .icon-car-selected { background-position: -9px -170px; }

.emoji-menu-tabs .icon-grid { background-position: -9px -248px; }

.emoji-menu-tabs .icon-grid-selected { background-position: -9px -222px; }

.emoji-menu-tabs .icon-smile,
.emoji-menu-tabs .icon-flower,
.emoji-menu-tabs .icon-bell,
.emoji-menu-tabs .icon-car,
.emoji-menu-tabs .icon-grid {
    opacity: 0.7;
}

.emoji-menu-tabs .icon-smile:hover,
.emoji-menu-tabs .icon-flower:hover,
.emoji-menu-tabs .icon-bell:hover,
.emoji-menu-tabs .icon-car:hover,
.emoji-menu-tabs .icon-grid:hover {
    opacity: 1;
}

.emoji-menu .emoji-items-wrap {
    position: relative;
    height: 174px;
    overflow: scroll;
}

.emoji-menu .emoji-items {
    padding-right: 8px;
    outline: 0 !important;
}

.emoji-menu img {
    width: 20px;
    height: 20px;
    vertical-align: middle;
    border: 0 none;
}

.emoji-menu .emoji-items a {
    margin: -1px 0 0 -1px;
    padding: 5px;
    display: block;
    float: left;
    border-radius: 2px;
    border: 0;
}

.emoji-menu .emoji-items a:hover {
    background-color: #edf2f5;
}

.emoji-menu:after {
    content: ' ';
    display: block;
    clear: left;
}

.emoji-menu a .label {
    display: none;
}
</style>