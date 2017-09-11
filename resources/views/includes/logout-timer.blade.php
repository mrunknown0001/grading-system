<!-- <script type="text/javascript">
var idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 600000); 
    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 1) {
        var base_url = window.location.origin;
        $.get(base_url + '/logout');
        alert('Session Expired! You need to Login again.');
        window.location = base_url;
        window.location.reload();
    }
}
</script> -->