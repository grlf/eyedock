function sendEmail () {
	
	jQuery('#email_spinner').show();

	var content = jQuery('#lens_report_content').html();
	var email_to = jQuery('#email_to').val();
	var email_subject = jQuery('#email_subject').val();
	var email_body = jQuery('#email_body').val();
	var email_contact = jQuery('#email_contact').val();
	var email_comp_name = jQuery('#email_comp_name').val();
	var email_comp_id = jQuery('#email_comp_id').val();
	var report_content = jQuery('#lens_report_content').html();
	
	//report_content = report_content.replace("<br>", "\n");
	
	var last_email = jQuery('#last_email').val();
	var last_date = new Date(last_email);
	var date = new Date();
	
	var days = (date.getTime() - last_date.getTime()) / ( 24 * 60 * 60 * 1000 );
	
	var days = Math.floor(days); 
	
	if (days < 90) {
		var doConfirm = confirm ("Are you sure you want to send this email? Your last email to " + email_comp_name +" was sent "+ last_email +" (" + days + " days ago) ");
		if (doConfirm == false) {
			jQuery('#email_spinner').hide();
			return;
		}
	}

	jQuery.ajax({
				type: "POST",
				url: "/administrator/components/com_pncompanies/utilities/handleReportForm.php",
				data: { 'to': email_to,
						'subject': email_subject,
						'email_body':  email_body,
						'report_content': report_content,
						'contact': email_contact,
						'company': email_comp_name,
						'id': email_comp_id,
				},
				dataType: "html",
				success: function(data) {
					jQuery('#email_confirmation').html("email sent to " + email_to + " on " + date );
					jQuery('#email_spinner').hide();
					//alert(email_body);
				  }
			});
}