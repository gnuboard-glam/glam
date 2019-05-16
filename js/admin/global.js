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
	if(location.href.indexOf('glam/admin')){
		document.body.className += '_glam';
	}
	// set title
	byId('logo').innerHTML = byId('hd').firstElementChild.innerText;

	// set license
	const year             = (new Date).getFullYear();
	byId('ft').appendChild(
		tag('p', {
			innerHTML: `Includes 2016~${year} <b>GLAM</b> presented by <em>liss.work</em>`
		})
	)
});

// rental
if (location.href.indexOf('glam/admin/rental/prices') > -1) {
	console.log('ok');
	window.addEventListener('DOMContentLoaded', () => {
		Array.from(document.querySelectorAll('.rental-prices input')).forEach(input => {

			if (input.name.indexOf('update[') < 0) {
				input.addEventListener('change', enableUpdate);
			} else {
				input.closest('.rental-price')['_itemUpdate'] = input;
			}
		});
	});

	function enableUpdate() {
		const group = this['_itemGroup'] || (this['_itemGroup'] = this.closest('.rental-price'));
		if (this.name.indexOf('update[') < 0) {
			group.className += ' _updated';
			group['_itemUpdate'].checked = true;
		}
	}
}