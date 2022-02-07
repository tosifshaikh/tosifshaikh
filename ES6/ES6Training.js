const calculateInterest = (formObj) => {
    if(validateInputs(formObj)) { 
    let data = getCalculation(formObj);
    inputdata(formObj); 
    displayGrid(data); 
    resetValues(formObj); 
    } 
  } 
const resetValues = (formObj) => formObj.reset(); 
const inputdata = (formObj) => { 
this.htmlStr = '<div class="form-group row">'; 
this.htmlStr += `<div class="col-sm-4">Amount
: ${formObj.amount.value}, Interest Rate (%): ${formObj.rate.value}, Period : ${formObj.year.value}</div>`; 
this.htmlStr += '</div>'; 
let inputDiv = document.getElementById('inputDiv'); 
    if (inputDiv) { 
    inputDiv.classList.add('border');
    inputDiv.innerHTML = this.htmlStr ;
     } 
    } 
const displayGrid = (data) => { 
this.htmlStr = '<table class="table table-striped">'; 
this.htmlStr += '<thead><tr>'; 
const Headers = ['Years', 'Year Interest', 'Total Interest','Balance']; 
Headers.forEach((e) => this.htmlStr += `<th>${e}</th>` ); 
this.htmlStr += '</tr></thead><tbody>';
data.forEach((e,t) => { 
this.htmlStr +=
`<tr><td>${t+1}</td><td>${e.yearinterest}</td><td>${e.earnedInterest}</td><td>${e.amount}</td></tr>`; }); 
this.htmlStr += '</tbody></table>'; 
let dataGrid = document.getElementById('dataGrid'); 
if (dataGrid) {
    dataGrid.classList.add('border'); 
    dataGrid.innerHTML = this.htmlStr ; 
    }
}
const getCalculation = (formObj) => {
	let dataObj = [];
	for(let i = 0; i < parseInt(formObj.year.value); i++) {
		dataObj[i] ={};
		if(i == 0) {
			let yearinterest = (Number(formObj.rate.value) / 100) * Number(formObj.amount.value);
			let earnedInterest = yearinterest;
			let amount = (yearinterest + Number(formObj.amount.value));
			dataObj[i] = {yearinterest : yearinterest.toFixed(2), earnedInterest : earnedInterest.toFixed(2), amount : amount.toFixed(2)}
		}
		else {
			let yearinterest = ((Number(formObj.rate.value) / 100) * Number(dataObj[i-1].amount));
			let earnedInterest = yearinterest + Number(dataObj[i-1].earnedInterest);
			let amount = (yearinterest + Number(dataObj[i-1].amount));
			dataObj[i] = {yearinterest : yearinterest.toFixed(2), earnedInterest : earnedInterest.toFixed(2) , amount : amount.toFixed(2)}
		}	
	}
	return dataObj;
}
const validateInputs = (formObj) => {
		let	obj = {amount : formObj.amount.value,
		 rate : formObj.rate.value,
		 year : formObj.year.value
		}
	let msgObj = {feedbackamount : 'Amount', feedbackrate : 'Rate', feedbackyear : 'Period'}
	const regex = /^(0|[1-9]\d*|(?=\.))(\.\d+)?$/, className = 'is-invalid';
	let querySelector = '', returnVal = true;
	for (let key in obj) {
		let isValid = true;
		 if(regex.test(obj[key].trim()) === false || obj[key] == '0') {
			 querySelector +=(querySelector == '') ? `#feedback${key}`: `,#feedback${key}`;
			 isValid = false;
		 }
		let jsElement = formObj[key];
		if(isValid == true) {
			jsElement.classList.remove(className);
		} else {
			jsElement.classList.add(className);
		}		
	} 
	if(querySelector !='')
	{
		let divElement = document.querySelectorAll(querySelector),divLen = divElement.length;
		for(let i = 0; i < divLen ; i++){
			if(divElement){
				divElement[i].innerHTML = `Invalid ${msgObj[divElement[i].id]}`;
			}
		}
		returnVal = false;
	}
	return returnVal;
} 
