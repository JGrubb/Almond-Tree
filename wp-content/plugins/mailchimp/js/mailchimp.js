var mce_jQuery = jQuery.noConflict();
mce_jQuery(document).ready( function($) {
    mce_jQuery('#mc_submit_type').val('js');
  options = { url: mc_ajax_url, type: 'POST', dataType: 'text',
                beforeSubmit: mc_beforeForm, 
                success: mc_success
            };
  mce_jQuery('#mc_signup_form').ajaxForm(options);

});

function mc_beforeForm(){
    try{
        mce_jQuery('#mc_signup_submit').attr("disabled","disabled");
    }catch(e){}
}
function mc_success(data){
    try{
        mce_jQuery('#mc_signup_submit').attr("disabled","");
    }catch(e){}
    mce_jQuery('#mc_message').html(data);
    var reg = new RegExp("class='mc_success_msg'", 'i');
    if (reg.test(data)){
        mce_jQuery('#mc_signup_form').each(function(){
	        this.reset();
    	});
    	mce_jQuery('#mc_submit_type').val('js');
    }
}
