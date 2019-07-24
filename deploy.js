const FtpDeploy = require('ftp-deploy');
const ftpDeploy = new FtpDeploy();

const path = require('path');
const minimist = require('minimist');
const inquirer = require('inquirer');

const root = path.normalize(__dirname + '/../../');

const defaultCofig = {
	port: 21,
	localRoot: root,
	exclude: [
		'node_modules/**/*',
		'**/node_modules/**/*',
		'**/package-lock.json',
		'**/*.scss',
		'**/*.less',
		'**/*.md'
	]
};

ftpDeploy.on('uploading', data => {
	const {transferredFileCount: done, totalFilesCount: total, filename} = data;
	const percent = Math.round(done / total * 100) + '%';
	console.info(`[${percent}][${done}/${total}] \x1b[36m${filename}\x1b[0m`);
});

ftpDeploy.on('upload-error', function (data) {
	console.error(`upload fails \x1b[31m${data.filename}\x1b[0m`);
});

console.log('');
console.log('Ftp Deploy Helper');
console.log('If you want to stop the request, press Ctrl + C');

const TARGET_ALL = 'Everything';
const TARGET_THEME = 'Theme';
const TARGET_THEME_CONTENTS = 'Theme Contents ans Script, Styles';

const targetPaths = {
	[TARGET_ALL]: ['**/*'],
	[TARGET_THEME]: ['theme/**/*'],
	[TARGET_THEME_CONTENTS]: [
		'.git/**/*',
		'**/.git/**/*',
		'.idea/**/*',
		'**/.idea/**/*',
		'theme/**/contents/**/*',
		'theme/**/css/.css',
		'theme/**/js/*.js'
	]
};

var argv = minimist(process.argv.slice(2), {
	host: null,
	id: null,
	password: null,
	root: null
});

const ask = [
	{
		type: 'list',
		name: 'target',
		message: 'Upload Target',
		choices: [
			TARGET_ALL,
			TARGET_THEME,
			TARGET_THEME_CONTENTS
		]
	}
];

if (!argv.host) {
	ask.push({
		type: 'input',
		name: 'host',
		message: 'Ftp Host'
	});
}

if (!argv.id) {
	ask.push({
		type: 'input',
		name: 'id',
		message: 'Ftp ID'
	});
}

if (!argv.password) {
	ask.push({
		type: 'input',
		name: 'password',
		message: 'Ftp Password'
	});
}

if (!argv.root) {
	ask.push({
		type: 'input',
		name: 'root',
		message: 'Ftp Remote Root Path',
		default() {
			return '/www'
		}
	})
}

inquirer.prompt(ask).then(answer => {
	const include = targetPaths[answer.target];
	const user = answer.id || argv.id;
	const password = answer.password || argv.password;
	const host = answer.host || argv.host;
	let remoteRoot = answer.root || argv.root;
	if (!remoteRoot.startsWith('/')) {
		remoteRoot = '/' + remoteRoot;
	}

	const config = {
		...defaultCofig,
		include,
		user,
		password,
		host,
		remoteRoot
	};

	ftpDeploy.deploy(config)
		.then(results => {
			console.log(`Upload Complete!`);
			if (results) {
				Object.keys(results).forEach(key => {
					const result = results[key];
					if (!key.match(/\d+/)) {
						console.log(key, result);
					}
				});
			}
		})
		.catch(error => console.error(error));
});
