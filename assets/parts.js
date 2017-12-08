let columns = {
  "PartID": false,
  "PartName": true,
  "PartNumber": true,
  "Suppliers": true,
  "Category": true,
  "Description01": true,
  "Description02": false,
  "Description03": false,
  "Description04": false,
  "Description05": false,
  "Description06": false,
  "Price": true,
  "Estimated Shipping Cost": true,
  "Associated image filename1": true,
  "Associated image filename2": false,
  "Associated image filename3": false,
  "Associated image filename4": false,
  "Shipping Weight": true,
  "Notes": true
};

const colsNames = [
  "PartID",
  "PartName",
  "PartNumber",
  "Suppliers",
  "Category",
  "Description01",
  "Description02",
  "Description03",
  "Description04",
  "Description05",
  "Description06",
  "Price",
  "Estimated Shipping Cost",
  "Associated image filename1",
  "Associated image filename2",
  "Associated image filename3",
  "Associated image filename4",
  "Shipping Weight",
  "Notes"
];

let scroll;
let data;

// PartID	int(10) unsigned zerofill Auto Increment	 
// PartName	varchar(255)	 
// PartNumber	int(11) NULL	 
// Suppliers	varchar(15) NULL	 
// Category	varchar(50) NULL	 
// Description01	varchar(255) NULL	 
// Description02	varchar(255) NULL	 
// Description03	varchar(255) NULL	 
// Description04	varchar(255) NULL	 
// Description05	varchar(255) NULL	 
// Description06	varchar(255) NULL	 
// Price	decimal(10,2) NULL	 
// Estimated Shipping Cost	decimal(10,2) NULL	 
// Associated image filename1	varchar(255) NULL	 
// Associated image filename3	varchar(255) NULL	 
// Associated image filename4	varchar(255) NULL	 
// Shipping Weight	int(11) NULL	 
// Associated image filename2	varchar(255) NULL	 
// Notes	varchar(255) NULL

let cartNumber = 0;

function add(id) {
  $("#add_" + id).empty();
  $('#item_' + id).addClass('selected');
  $("#add_" + id).append('<button class="u-full-height" onclick="remove(' + id + ')" style="width: 17px">-</button>');
  cart[id] = true;

  cartNumber++;
  $('#checkout').empty();
  $('#checkout').append('Checkout: items ' + cartNumber);
}

function remove(id) {
  $("#add_" + id).empty();
  $('#item_' + id).removeClass('selected');
  $("#add_" + id).append('<button class="u-full-height" onclick="add(' + id + ')" style="width: 17px">+</button>');
  delete cart[id];

  cartNumber -= 1;
  $('#checkout').empty();
  $('#checkout').append('Checkout: items ' + cartNumber);
}

const renderCols = () => {
  $('#partsHeaderCols').empty();

  let str = '<div class="divider"></div>';
  for (let i = 0; i < colsNames.length; i++) {
    if (columns[colsNames[i]]) {
      str += '<div class="col">' + colsNames[i] + '</div><div class="divider"></div>';
    }
  }

  $('#partsHeaderCols').append(str);
}

const virtscrollCallback = (start, stop) => {
  // console.log(indexToRender);
  $('#partsItems').empty();

  let str = '';
  for (let j = start; j < stop; j++) {
    btn = '<button class="u-full-height" onclick="add(' + Number(data[j]['PartID']) + ')" style="width: 17px">+</button>';
    style = '';
    if (cart[Number(data[j]['PartID'])]) {
      btn = '<button class="u-full-height" onclick="remove(' + Number(data[j]['PartID']) + ')" style="width: 17px">-</button>';
      style += ' selected';
    }
    str += '<div class="item' + style + '" id="item_' + Number(data[j]['PartID']) + '"><div class="u-full-height" id="add_' + Number(data[j]['PartID']) + '">' + btn + '</div>';

    for (let i = 0; i < colsNames.length; i++) {
      if (columns[colsNames[i]]) {
        str += '<div class="col">' + data[j][colsNames[i]] + '</div><div class="divider"></div>';
      }
    }

    str += '</div>';
  }

  $('#partsItems').append(str);
}

$.getJSON("backend/getParts.php", function (newData) {

  data = newData;


  // console.log(data[0]);

  // divContainer, divTopSpacer, divItems, elementHeight, numberOfElements, renderCallback
  const scrollObj = new virtScroll('#partsViewer', '#partsTopDiv', '#partsItems', '#partsHolder', 45, data.length, virtscrollCallback);
  scroll = scrollObj;

  renderCols();

});


const setupColChooser = () => {
  $('#colMenu').empty();

  let str = '';
  for (let i = 0; i < colsNames.length; i++) {
    if (columns[colsNames[i]]) {
      str += '<input type="checkbox" id="' + i + '" checked> ' + colsNames[i] + '<br>'
    } else {
      str += '<input type="checkbox" id="' + i + '"> ' + colsNames[i] + '<br>'
    }
  }

  $('#colMenu').append(str);

  for (let i = 0; i < colsNames.length; i++) {
    $('#' + i).change(function () {
      console.log(i + " " + this.checked);
      columns[colsNames[i]] = this.checked;
    });
  }
}

const setupSortChooser = () => {
  $('#colMenu2').empty();

  let str = '';
  for (let i = 0; i < colsNames.length; i++) {
    if (columns[colsNames[i]]) {
      str += colsNames[i] + ': <button onclick="sortAsc(' + i + ')">asc</button><button onclick="sortDsc(' + i + ')">dsc</button><br>'
    }
  }

  $('#colMenu2').append(str);
}



$("#sortColumns").click(function () {
  $('#colOverlay2').css('display', 'flex');
  setupSortChooser();
});
$('#quit2').click(function () {
  $('#colOverlay2').css('display', 'none');
  // renderCols();
  // scroll._render(true);
});

$("#partColumns").click(function () {
  $('#colOverlay').css('display', 'flex');
  setupColChooser();
});

$('#quit').click(function () {
  $('#colOverlay').css('display', 'none');
  renderCols();
  scroll._render(true);
});

$('#estimate').click(function () {

  if (price > 0 && weight > 0) {
    var zip = $('#zip').val();

    $.ajax({
      url: "ajax.php",
      type: "POST",
      data: {
        'trigger': 'getCost',
        price: price,
        zip: zip,
        weight: weight
      },
      success: function (result) {
        result = JSON.parse(result)
        $("#cost").append(result.TotalCost);
      }

    });


  }
});

$("#checkout").click(function () {
  $('#Overlay').css('display', 'flex');
  setupCheckout();
});
$('#quit3').click(function () {
  $('#Overlay').css('display', 'none');
  // renderCols();
  // scroll._render(true);
});

let price;
let weight;

function setupCheckout() {
  $('#colMenu3').empty();
  str = `<table>
  <tr>
    <th>
      Part name
    </th>
    <th>
      Cost
    </th>
    <th>
      Weight
    </th>
    <th>
      Quantity
    </th>
  </tr>`

  price = 0;
  weight = 0;

  const keys = Object.keys(cart);
  const keysLen = keys.length;
  let currentFound = 0;
  for (let i = 0; i < data.length && currentFound < keysLen; i++) {
    if (keys.indexOf(Number(data[i]['PartID']) + "") > -1) {
      currentFound += 1;
      price += Number(data[i]['Price']);
      weight += Number(data[i]['Shipping Weight']);
      str += "<tr><td>" + data[i]['PartName'] + "</td><td>" + data[i]['Price'] + "</td><td>" + data[i]['Shipping Weight'] + "</td>" +
        "<td><input required type=\"number\" name=\"" + data[i]['PartName'] + "\" value=\"1\"/></td></tr>";
    }
  }

  str += '<tr><td>TOTAL:</td><td>' + price + '</td><td>' + weight + '</td></tr></table>';
  $('#colMenu3').append(str);
}



const sortAsc = (i) => {
  $.getJSON("backend/getParts.php?asc=" + colsNames[i], function (newData) {

    data = newData;
    scroll._render(true);
  });
  $('#colOverlay2').css('display', 'none');
}

const sortDsc = (i) => {
  $.getJSON("backend/getParts.php?dsc=" + colsNames[i], function (newData) {

    data = newData;
    scroll._render(true);
  });
  $('#colOverlay2').css('display', 'none');
}