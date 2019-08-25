$(document).ready(function () {
	
	// Show login form
	$("#loginBtn").click(function (e) {
		$("#loginBox").toggleClass("hide");
		if ($(window).width() > 767) {
			return false;
		}
	});
	
	
	// toggle dropdown menu
	$("ul.dropdown-menu [data-toggle=dropdown]").on("click", function(event) {
		event.preventDefault(); 
		event.stopPropagation(); 
		$(this).parent().siblings().removeClass("open");
		$(this).parent().toggleClass("open");
	});


	// google login
	//startApp();


	$(".multiupload").change(function() {
		$("#assignment_form").submit();
	});
	
	
	$(".add_image_to_tiny").click(function() {
		var link = $(this).attr("rel");
		tinymce.activeEditor.insertContent("<img src='" + link + "'>");
		return false;
	});


	$(".add_youtube_to_tiny").click(function() {
		var link = $(this).attr("rel");
		tinymce.activeEditor.insertContent("<iframe width='560' height='315' src='https://www.youtube.com/embed/" + link + "' frameborder='0' allowfullscreen></iframe>");
		return false;
	});
	
	
	$(".assignments-overview.list input:checkbox").change(function() {
		if ($(".assignments-overview.list input:checkbox:checked").length > 0) {
			$("#new-assignment-group").removeClass("disabled");
		} else {
			$("#new-assignment-group").addClass("disabled");
		}
	});
	
	function sendResizeRequest() {
    var height = $("windows").height();

    if (window.location != window.parent.location)
        window.top.postMessage(height, "http://www.fll.sk");
	}
	
	
	// http://kempelen.ii.fmph.uniba.sk/rl/?page=new-solution hidden save alert
	$("#save_alert").delay(2000).fadeTo("slow", 0);
	
	
	// remove jury
	$(".remove_jury").click(function() {
		if (!confirm("Zmazať rozhodcu?"))
			return false;
	});
	
	// best solution jury
	$(".best_alert").click(function() {
		if (!confirm("Označiť alebo zrušiť toto riešenie ako najlepšie?"))
			return false;
	});
});


// API Google login
function signIn() {
	var auth2 = gapi.auth2.getAuthInstance();
	auth2.signIn().then(function () {

		var email = auth2.currentUser.get().getBasicProfile().getEmail();
		var password_hint = auth2.currentUser.get().getAuthResponse().login_hint;

		$.ajax({
			type: "POST",
			url: "google_login/google_login.php",
			data: {
				email: email,
				password_hint: password_hint
			},
			success: function(result) {
				if (result == "registration") {
					window.location.href = "?page=new-registration&email=" + email + "&password_hint" + password_hint;
				}
			}
		});
	});
}


var googleUser = {};
var startApp = function() {
	gapi.load("auth2", function(){
		auth2 = gapi.auth2.init({
			client_id: "1084884863932-jhq352a4i78vmtg4138b539vfcc81q7l.apps.googleusercontent.com",
			cookiepolicy: "single_host_origin",
		});
		attachSignin(document.getElementById("google-login"));
	});
};


function attachSignin(element) {
	auth2.attachClickHandler(element, {},
		function(googleUser) {
			//console.log(googleUser.getAuthResponse().login_hint);
		}, function(error) {
			alert(JSON.stringify(error, undefined, 2));
		});
}


// remember scroll position
$(window).scroll(function() {
	sessionStorage.scrollTop = $(this).scrollTop();
});

$(document).ready(function() {
	if (sessionStorage.scrollTop != "undefined") {
		$(window).scrollTop(sessionStorage.scrollTop);
	}
});