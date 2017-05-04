(function($) {
	
	$('select#Form_EditForm_SharingType').entwine({
		onmatch: function(e) {
			this._super();
			this.toggle();
		},
		onchange: function(e) {
			this._super();
			this.toggle();
		},
		toggle: function () {
			// hide all
			$('.field.social-sharing-networks').hide();
			$('#Form_EditForm_ShareAddThisCode_Holder').hide();
			// show by type
			if ($(this).val() == 'Links') {
				$('.field.social-sharing-networks').show();
			} else if ($(this).val() == 'Buttons') {
				$('.field.social-sharing-networks').show();
			} else if ($(this).val() == 'AddThis') {
				$('#Form_EditForm_ShareAddThisCode_Holder').show();
			}
		}
	});
	
})(jQuery);