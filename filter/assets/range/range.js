$(function() {
	$('.filter-range-field').each(function() {
		var $this = $(this),
			$hidden = $this.find(':hidden');

		$this.find(':text').removeAttr('name').change(inputOnChange);

		$hidden.prop('disabled', $hidden.val() == '');
	});

	function inputOnChange()
	{
		var $field = $(this).closest('.filter-range-field'),
			$hidden = $field.find(':hidden'),
			$from = $field.find(':text:first'),
			$to = $field.find(':text:last'),
			value = $from.val() + '_' + $to.val();

		$hidden.val(value).prop('disabled', value == '_');
	}
});
