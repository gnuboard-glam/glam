function tag(name, properties) {
	const node = document.createElement(name);
	if (properties) {
		Object.keys(properties).forEach(key => {
			node[key] = properties[key];
		});
	}
	return node;
}

function byId(id) {
	return document.getElementById(id);
}

document.addEventListener('DOMContentLoaded', () => {
	if (location.href.indexOf('glam/admin')) {
		document.body.className += '_glam';
	}
	// set title
	byId('logo').innerHTML = byId('hd').firstElementChild.innerText;

	// set license
	const year = (new Date).getFullYear();
	byId('ft').appendChild(
		tag('p', {
			innerHTML: `Includes 2016~${year} <b>GLAM</b> presented by <em>liss.work</em>`
		})
	)
});


(function (window, document) {
	var flag      = 'data-input-updated-check';
	var storeName = '_inputUpdateChecker';

	function rowUpdateCheck() {
		Array.from(document.querySelectorAll('[' + flag + ']'))
			.forEach(containerSet);
	}

	function containerSet(container) {
		var groupSelector = container.getAttribute(flag) || 'tr';

		Array.from(container.querySelectorAll('input'))
			.forEach(function (input) {
				if (input.name.indexOf('update[') < 0) {
					input.addEventListener('change', onChange);
					input[storeName] = {
						groupSelector: groupSelector
					};
				} else {
					container[storeName] = input;
				}
			});
	}

	function onChange() {
		var store      = this[storeName];
		var group      = store['group'] ||
						 (store['group'] = this.closest(store['groupSelector']));
		var update     = group['update'] ||
						 (group['update'] = group.querySelector('input[name^=update]'));
		group.className += ' _updated';
		update.checked = true;
	}

	window.addEventListener('DOMContentLoaded', rowUpdateCheck);
})(window, document);