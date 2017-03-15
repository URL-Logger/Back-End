var filterId = 0;

function tileLimit(id) {
	return `<div class='item' id='filter_`+id+`'>
		<div class='title'>Limit Rows</div>
		<div class='content'>
			<input type='number' name='limit' value='1000' onchange='update()'/></br>
		</div>
		<a class='exit' onclick="removeFilter('filter_`+id+`')">x</a>
	</div>
	`;
}

function tileDate(id) {
	return `<div class='item' id='filter_`+id+`'><input type='hidden' name='date[]' value='`+id+`'/>
		<div class='title'>Date</div>
		<div class='content'>
			<input type='date' name='date_`+id+`' value='<?php echo date("Y-m-d"); ?>' onchange='update()'/></br>
		</div>
		<a class='exit' onclick="removeFilter('filter_`+id+`')">x</a>
	</div>
	`;
}

function tileDateRange(id) {
	return `<div class='item' id='filter_`+id+`'><input type='hidden' name='daterange[]' value='`+id+`'/>
		<div class='title'>Date Range</div>
		<div class='content'>
			Start <input type='date' name='daterange_start_`+id+`' value='<?php echo date("Y-m-d"); ?>' onchange='update()'/></br>
			End <input type='date' name='daterange_end_`+id+`' value='<?php echo date("Y-m-d"); ?>' onchange='update()'/></br>
		</div>
		<a class='exit' onclick="removeFilter('filter_`+id+`')">x</a>
	</div>
	`;
}

function tileUserId(id) {
	return `<div class='item' id='filter_`+id+`'><input type='hidden' name='userid[]' value='`+id+`'/>
		<div class='title'>Users by ID</div>
		<div class='content'>
			<input type='text' name='userid_`+id+`' onchange='update()'/></br>
		</div>
		<a class='exit' onclick="removeFilter('filter_`+id+`')">x</a>
	</div>
	`;
}

function addFilter() {
	var sel = document.getElementById("sel_filter");
	var filterType = sel.options[sel.selectedIndex].value;
	sel.selectedIndex = 0;
	var filters = document.getElementById("list_filters");
	var container = document.createElement("div");
	switch(filterType) {
		case "Limit Rows":
			if(document.getElementsByName('limit').length == 0) {
				container.innerHTML = tileLimit(filterId++);
				filters.appendChild(container);
			}
			break;
		case "Date":
			container.innerHTML = tileDate(filterId++);
				filters.appendChild(container);
			break;
		case "Date Range":
			container.innerHTML = tileDateRange(filterId++);
			filters.appendChild(container);
			break;
		case "User ID":
			container.innerHTML = tileUserId(filterId++);
			filters.appendChild(container);
			break;
		default:
			return;
	}
	update();
}

function removeFilter(id) {
	var obj = document.getElementById(id);
	obj.parentElement.removeChild(obj);
	update();
}

function update() {
	$.post("download.php?preview",
		$("#form").serialize(),
		function(data) {
			document.getElementById("display").innerHTML = data;
		});
}