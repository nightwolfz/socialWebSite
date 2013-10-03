<article id="content1" class="clearfix">
    
    <h1 class="warning">REGISTRATION DISABLED FOR NOW.</h1>
    
    <form id="registerForm" method="post" action="/index/login">
        <h1>Enter username</h1>
        <input type="text" id="username" name="username" value="">
        
        <h1>Enter password</h1>
        <input type="text" id="password" name="password" value="">
        
        <h1>Enter email</h1>
        <input type="text" id="email" name="email" value="">
        
        <h1>Chose country</h1>
        <input type="text" id="country" name="country" value="">
        
        <h1>Chose gender</h1>
        <input type="radio" id="genderm" name="gender" value="m"> 
        <label for="genderm">Male</label>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" id="genderf" name="gender" value="f"> 
        <label for="genderf">Female</label>
        
        <input type="hidden" name="action-login" value="1">
    </form>
    <a class="uibutton blue" onclick="submitCreateAccount();">Create my account now!</a>
</article>


<script type="text/javascript">
$(document).ready(function(){
    console.log("<?=$_SERVER["REMOTE_ADDR"]?>");
    /*if (navigator.geolocation){
        navigator.geolocation.getCurrentPosition( successCallback, errorCallback, {maximumAge:600000, timeout:0} );
    } else {
        alert("Sorry, your browser does not support geolocation services.");
    }*/
    function successCallback(position) {
        /*yqlgeo.get(position.coords.latitude, position.coords.longitude, function(o){
            console.log(o.place);
        })*/
    }
    //Bing maps key: AtQDZgzwR2nkrFKjlE72xoiHDsxmeisiax3yXWkEvR24Cf3ddACByhLEAOPhC2KY

    $.get("http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20pidgets.geoip%20where%20ip%3D'<?=$_SERVER["REMOTE_ADDR"]?>'&format=json&env=http%3A%2F%2Fdatatables.org%2Falltables.env", function(data) {
        $('#country').val(data.query.results.Result.country_code);
    });

//http://dev.virtualearth.net/REST/v1/Locations/point?includeEntityTypes=Address&includeNeighborhood=1&key=AtQDZgzwR2nkrFKjlE72xoiHDsxmeisiax3yXWkEvR24Cf3ddACByhLEAOPhC2KY
    function errorCallback(error) {
      switch(error.code) {
        case error.PERMISSION_DENIED: console.warn("user did not share geolocation data");
          break;
        case error.POSITION_UNAVAILABLE: console.warn("could not detect current position");
          break;
        case error.TIMEOUT:
          navigator.geolocation.getCurrentPosition(successCallback, errorCallback);// Acquire a new position object.
          break;
      };
    }
    
    // Assign default values for input fields
    applyDefaultValue = function(e, val) {
        if (typeof e != 'undefined'){
                e.style.color = '#666';
                e.value = val;
                e.onfocus = function() {if(this.value == val) {this.style.color = '';this.value = '';}};
                e.onblur = function() {if(this.value == '') {this.style.color = '#666';this.value = val;}};
        }
    };
    var formArray = {'username':'Username...','password':'Password...','email':'Email...'};
    for (f in formArray){
        applyDefaultValue($('#registerForm #'+f)[0], formArray[f]);
    }
    
    submitCreateAccount = function(){
        var user = $('#registerForm #username').val()!=formArray['username'] ? $('#registerForm #username').val() : '';
        var pass = $('#registerForm #password').val()!=formArray['password'] ? $('#registerForm #password').val() : '';
        var mail = $('#registerForm #email').val()!=formArray['email'] ? $('#registerForm #email').val() : '';
        
        var gend = $('#registerForm input[name=gender]:checked').val();
        
        $.post("/register/done", { username: user, password: pass, email: mail, gender: gend }, function(error){console.log('ee');
                if (error == 'OK'){
                    $('#content1 form')[0].submit();
                }else{
                    for (i in error) showErrorMessage(error[i]);
                }
            }, "json");
    };
    
    showErrorMessage = function(msg){
        $('section .grid_12').prepend($('<div class="textError warning"/>').html(msg));
        setTimeout(function(){
            $('.textError').fadeOut(2000, function(){
                $('.textError').remove();
            });
        }, 500);
    };
    
    
    window.currentStep = 1;
    
    //for (var step = 2; step < 10; step++) $('#content'+step).css({opacity:0, left:'50px'});
    

    slideOutAnimate = function(el, callback){
        $(el).animate(window.slideAnimation, { queue: false, duration: 400, easing:'easeInOutQuart', 
            complete:function() {
                $(el).hide(0);
                console.log('slideout');
                callback();
            } 
        });
    };
    
    slideInAnimate = function(el){console.log('slidein');
    console.log(el);
        $(el).animate({opacity: 1, left: '-50px', leaveTransforms:false, useTranslate3d:true}, { queue: false, duration: 400, easing:'easeInOutQuart', 
            complete:function() {
                console.log('finish');
                $(el).show(0);
            }
        });
    };
    
    
    registerNextStep = function(){
        slideOutAnimate('#content'+window.currentStep, function(){
            ++window.currentStep;
            var el = '#content'+window.currentStep;
            slideInAnimate(el);$(el).show(0);
        });
        
        console.log(window.currentStep);
    }
    registerPreviousStep = function(){
        slideOutAnimate('#content'+window.currentStep, function(){
            --window.currentStep;
            var el = '#content'+window.currentStep;
            slideInAnimate(el);$(el).show(0);
        });
        
        console.log(window.currentStep);
    }
    
    /*yqlgeo.get('62.235.221.13',function(o){
            console.log(o);
    });*/
});
</script>
<style>
    .textError {
        font-size: 1.5em;
        font-family: 'Segoe WPC', 'Segoe UI', Helvetica, Arial, Verdana, 'Arial Unicode MS', sans-serif;
        color: #dd4b39;
        font-variant: small-caps;
    }
    #registerForm label {
        position: relative;
        top: -6px;
        font-size: 16px;
    }
</style>
<!-- for logging in -->
<form action="/index/login" method="post">
    
</form>