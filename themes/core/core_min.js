if (top.location!= self.location) {top.location = self.location.href}setTimeout(function(){window.scrollTo(0,1)},100);$wptouch=jQuery.noConflict();function wptouch_switch_confirmation(){if(document.cookie && document.cookie.indexOf("wptouch_switch_cookie")>-1){$wptouch("#wptouch-switch-link a#switch-link").toggleClass("offimg");setTimeout('switch_delayer()',1250);}else{var answer=confirm("Switch to regular view?\n \n You can switch back to mobile view again in the footer.");if(answer){$wptouch("#wptouch-switch-link a#switch-link").toggleClass("offimg");setTimeout('switch_delayer()',1250);}}}jQuery.fn.fadeToggle=function(speed,easing,callback){return this.animate({opacity:'toggle'},speed,easing,callback);};function bnc_jquery_menu_drop(){$wptouch('#wptouch-menu').fadeToggle(400);$wptouch("#headerbar-menu a").toggleClass("open");}function bnc_jquery_login_toggle(){$wptouch('#wptouch-login').fadeToggle(400);}function bnc_jquery_cats_open(){jQuery('#cat').focus();}function bnc_jquery_tags_open(){jQuery('#tag-dropdown').focus();}function bnc_jquery_acct_open(){jQuery('#acct-dropdown').focus();}function bnc_showhide_coms_toggle(){$wptouch('#commentlist').slideToggle(400);$wptouch("img#com-arrow").toggleClass("com-arrow-down");}function commentAdded(){if($wptouch('#errors')){$wptouch('#errors').hide();}if($wptouch('#nocomment')){$wptouch('#nocomment').hide();}if($wptouch('#hidelist')){$wptouch('#hidelist').hide();}$wptouch("#commentform").hide();$wptouch("#some-new-comment").fadeIn(2000);$wptouch("#refresher").fadeIn(2000);}