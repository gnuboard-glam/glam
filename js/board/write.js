(function () {
	'use static';
	var $form;
	var $submits;
	var $isFiltering;
	var $hasToken;

	document.addEventListener('DOMContentLoaded', function () {
		$form = document.forms['fwrite'];
		if (!$form) {
			console.warn('Cannot found form');
			return false;
		}
		$submits = Array.from($form.querySelectorAll('input[type=submit], button[type=submit]'));

		$form.addEventListener('submit', onSubmit);

		var elements = $form.elements;
		elements['wr_subject'].addEventListener('change', onForbiddenContentTargetChanged);
	});

	function onSubmit() {
		if ($isFiltering) {
			return false;
		}
		return true;
	}

	function onForbiddenContentTargetChanged() {
		var value = this.value;
		var request = new XMLHttpRequest();
		var type = this.name.indexOf('subject') > -1 ?
			'subject' :
			'content';

		request.open('POST', g5_bbs_url + '/ajax.filter.php');
		request.setRequestHeader('Content-Type', 'application/json');
		request.onreadystatechange = function () {
			if (request.readyState === 4) {
				if (request.status === 200) {
					var result = JSON.parse(request.responseText);
					var filtered = result[type];
					if (filtered) {
						alert('금지된 단어(' + filtered + ')가 포함되어 있습니다.');
						this.focus();
					} else {
						$submits.forEach(enables);
						$isFiltering = false;
					}
				} else {
					// error
				}
			}
		};

		var body = {
			subject: '',
			content: ''
		};

		body[type] = value;


		request.send(JSON.stringify(body));

		$isFiltering = true;
		$submits.forEach(disables);

	}

	function enables(target) {
		target.disabled = false;
	}

	function disables(target) {
		target.disabled = true;
	}
})();