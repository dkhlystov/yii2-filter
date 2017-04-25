$(function() {
	$('.filter-drop-down-field select').each(function() {
		var $this = $(this),
			value = $this.val();

		$('<input type="hidden">').attr('name', $this.attr('name')).val(value).prop('disabled', value == '').insertBefore($this);

		$this.removeAttr('name');
		$this.on('change', selectOnChange);
	});

	function selectOnChange()
	{
		var $this = $(this),
			$hidden = $this.prev(),
			value = $this.val();

		$hidden.val(value).prop('disabled', value == '');
	}
});
