jQuery(document).ready(function ($) {
    $("#repeater").createRepeater();
});

function eventFire(el, etype){
    if (el.fireEvent) {
      el.fireEvent('on' + etype);
    } else {
      var evObj = document.createEvent('Events');
      evObj.initEvent(etype, true, false);
      el.dispatchEvent(evObj);
    }
}

function addNew() {
    eventFire(document.getElementById('addMore'), 'click');
}