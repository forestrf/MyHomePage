//console.log(API.url(widgetID,'main.js'));

console.log('Test 1');

/////////////////////////////
// SET test
/////////////////////////////

var text_rnd = Math.random();
console.log('Test 1 Saving the text: '+text_rnd);

var comando = {
	'action':'set',
	'widget':widgetID,
	'variables':{'test':text_rnd}
};

API.call(comando, function(entrada){
	if(entrada['test']){
		console.log('Test 1 Text Saved.');
	}
	else{
		console.log('Test 1 Text NOT saved.');
	}
});



/////////////////////////////
// GET test
/////////////////////////////

var comando = {
	'action':'get',
	'widget':widgetID,
	'variables':'test'
};

API.call(comando, function(entrada){
	if(entrada['test']){
		console.log('Test 1 Got the text: '+entrada['test']);
	}
	else{
		console.log('Test 1 There is not a saved variable with that name.');
	}
});

// ----------------------------------------------------------------------------------------------------------------



console.log('Test 2');

/////////////////////////////
// Multiple SET test
/////////////////////////////

var text_rnd1 = Math.random();
var text_rnd2 = Math.random();
var text_rnd3 = Math.random();
console.log('Test 2 Saving the texts:\n'+text_rnd1+'\n'+text_rnd2+'\n'+text_rnd3);

var comando = {
	'action':'set',
	'widget':widgetID,
	'variables':{'test1':text_rnd1,'test2':text_rnd2,'test3':text_rnd3}
};

API.call(comando, function(entrada){
	for(var i in entrada){
		if(entrada[i]){
			console.log('Test 2 Text Saved ('+i+').');
		}
		else{
			console.log('Test 2 Text NOT saved ('+i+').');
		}
	}
});



/////////////////////////////
// Multiple GET test
/////////////////////////////

var comando = {
	'action':'get',
	'widget':widgetID,
	'variables':['test1','test2','test3']
};

API.call(comando, function(entrada){
	for(var i in entrada){
		if(entrada[i]){
			console.log('Test 2 Got the text ('+i+'): '+entrada[i]);
		}
		else{
			console.log('Test 2 There is not a saved variable with that name ('+i+').');
		}
	}
});

// ----------------------------------------------------------------------------------------------------------------



console.log('Test 3');

/////////////////////////////
// Global variable SET test
/////////////////////////////

var text_rnd = Math.random();
console.log('Test 3 Saving the text: '+text_rnd);

var comando = {
	'action':'set',
	'widget':'global',
	'variables':{'test':text_rnd}
};

API.call(comando, function(entrada){
	if(entrada['test']){
		console.log('Test 3 Text Saved.');
	}
	else{
		console.log('Test 3 Text NOT saved.');
	}
});



/////////////////////////////
// Global variable GET test
/////////////////////////////

var comando = {
	'action':'get',
	'widget':'global',
	'variables':'test'
};

API.call(comando, function(entrada){
	if(entrada['test']){
		console.log('Test 3 Got the text: '+entrada['test']);
	}
	else{
		console.log('Test 3 There is not a saved variable with that name.');
	}
});