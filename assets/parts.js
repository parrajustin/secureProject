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

//                                               
//                                               
//                                       lllllll 
//                                       l:::::l 
//                                       l:::::l 
//                                       l:::::l 
//      cccccccccccccccc   ooooooooooo    l::::l 
//    cc:::::::::::::::c oo:::::::::::oo  l::::l 
//   c:::::::::::::::::co:::::::::::::::o l::::l 
//  c:::::::cccccc:::::co:::::ooooo:::::o l::::l 
//  c::::::c     ccccccco::::o     o::::o l::::l 
//  c:::::c             o::::o     o::::o l::::l 
//  c:::::c             o::::o     o::::o l::::l 
//  c::::::c     ccccccco::::o     o::::o l::::l 
//  c:::::::cccccc:::::co:::::ooooo:::::ol::::::l
//   c:::::::::::::::::co:::::::::::::::ol::::::l
//    cc:::::::::::::::c oo:::::::::::oo l::::::l
//      cccccccccccccccc   ooooooooooo   llllllll
//                                               
//                                               
//                                               
//                                               
//                                               
//                                               
//                                               


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

$("#partColumns").click(function () {
  $('#colOverlay').css('display', 'flex');
  setupColChooser();
});

$('#quit').click(function () {
  $('#colOverlay').css('display', 'none');
  renderCols();
  scroll._render(true);
});

//                                                                                                                                                          
//                                                                                                                                                          
//                     hhhhhhh                                                     kkkkkkkk                                                   tttt          
//                     h:::::h                                                     k::::::k                                                ttt:::t          
//                     h:::::h                                                     k::::::k                                                t:::::t          
//                     h:::::h                                                     k::::::k                                                t:::::t          
//      cccccccccccccccch::::h hhhhh           eeeeeeeeeeee        cccccccccccccccc k:::::k    kkkkkkk ooooooooooo   uuuuuu    uuuuuuttttttt:::::ttttttt    
//    cc:::::::::::::::ch::::hh:::::hhh      ee::::::::::::ee    cc:::::::::::::::c k:::::k   k:::::koo:::::::::::oo u::::u    u::::ut:::::::::::::::::t    
//   c:::::::::::::::::ch::::::::::::::hh   e::::::eeeee:::::ee c:::::::::::::::::c k:::::k  k:::::ko:::::::::::::::ou::::u    u::::ut:::::::::::::::::t    
//  c:::::::cccccc:::::ch:::::::hhh::::::h e::::::e     e:::::ec:::::::cccccc:::::c k:::::k k:::::k o:::::ooooo:::::ou::::u    u::::utttttt:::::::tttttt    
//  c::::::c     ccccccch::::::h   h::::::he:::::::eeeee::::::ec::::::c     ccccccc k::::::k:::::k  o::::o     o::::ou::::u    u::::u      t:::::t          
//  c:::::c             h:::::h     h:::::he:::::::::::::::::e c:::::c              k:::::::::::k   o::::o     o::::ou::::u    u::::u      t:::::t          
//  c:::::c             h:::::h     h:::::he::::::eeeeeeeeeee  c:::::c              k:::::::::::k   o::::o     o::::ou::::u    u::::u      t:::::t          
//  c::::::c     ccccccch:::::h     h:::::he:::::::e           c::::::c     ccccccc k::::::k:::::k  o::::o     o::::ou:::::uuuu:::::u      t:::::t    tttttt
//  c:::::::cccccc:::::ch:::::h     h:::::he::::::::e          c:::::::cccccc:::::ck::::::k k:::::k o:::::ooooo:::::ou:::::::::::::::uu    t::::::tttt:::::t
//   c:::::::::::::::::ch:::::h     h:::::h e::::::::eeeeeeee   c:::::::::::::::::ck::::::k  k:::::ko:::::::::::::::o u:::::::::::::::u    tt::::::::::::::t
//    cc:::::::::::::::ch:::::h     h:::::h  ee:::::::::::::e    cc:::::::::::::::ck::::::k   k:::::koo:::::::::::oo   uu::::::::uu:::u      tt:::::::::::tt
//      cccccccccccccccchhhhhhh     hhhhhhh    eeeeeeeeeeeeee      cccccccccccccccckkkkkkkk    kkkkkkk ooooooooooo       uuuuuuuu  uuuu        ttttttttttt  
//                                                                                                                                                          
//                                                                                                                                                          
//                                                                                                                                                          
//                                                                                                                                                          
//                                                                                                                                                          
//                                                                                                                                                          
//             

let cartItems = {};

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

function calculatePriceAndWeight() {
  price = 0;
  weight = 0;

  let cartObj = [];
  
  const keys = Object.keys(cart);
  const keysLen = keys.length;
  let currentFound = 0;
  for (let i = 0; i < keysLen; i++) {
    console.log(cartItems[keys[i]]);
    price += Number(cartItems[keys[i]]['Price']) * (Number($('#count_' + keys[i]).val()) || 1);
    weight += Number(cartItems[keys[i]]['Shipping Weight']) * (Number($('#count_' + keys[i]).val()) || 1);

    let obj = {
      'name': cartItems[keys[i]]['PartName'],
      'weight': Number(cartItems[keys[i]]['Shipping Weight']),
      'price': Number(cartItems[keys[i]]['Price']),
      'quantity': Number($('#count_' + keys[i]).val()) || 1
    };
    cartObj.push(obj);
  }

  $('#hiddenCart').val(JSON.stringify(cartObj));
}

let listeners = [];

function setupCheckout() {
  const listenLen = listeners.length;
  for (let i = 0; i < listenLen; i++ ) {
    let temp = listeners.pop()
    temp.off("change paste keyup");
  }

  cartItems = {};
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
      cartItems[Number(data[i]['PartID'])] = data[i];
      currentFound += 1;
      // price += Number(data[i]['Price']);
      // weight += Number(data[i]['Shipping Weight']);
      str += "<tr><td>" + data[i]['PartName'] + "</td><td>" + data[i]['Price'] + "</td><td>" + data[i]['Shipping Weight'] + "</td>" +
        "<td><input required type=\"number\" id=\"count_" + Number(data[i]['PartID']) + "\" value=\"1\"/></td></tr>";
    }
  }

  calculatePriceAndWeight();

  str += '<tr><td>TOTAL:</td><td id="price">' + price + '</td><td id="weight">' + weight + '</td></tr></table>';
  $('#colMenu3').append(str);

  for (let i = 0; i < keysLen; i++) {
    $("#count_" + keys[i]).on("change paste keyup", () => {
      this.calculatePriceAndWeight();
      $('#price').empty();
      $('#price').append(price);
      $('#weight').empty();
      $('#weight').append(weight);
    });
    listeners.push($("#count_" + keys[i]));
  }
}


$('#estimate').click(function() {
  if (price > 0 && weight > 0) {
    const keys = Object.keys(cart);
    const keysLen = keys.length;
    for (let i = 0; i < keysLen; i++) {
      console.log($('#count_' + Number(data[i]['PartID'])).val());
    }

    $.getJSON( "backend/getCost.php?cost=" + price + "&zip=" + $('#zip').val() + "&weight=" + weight, function( answer ) {
    
      // console.log(answer);
      $('#cost').empty();
      $('#state').empty();
      
      $('#state').append(answer['State'] + " " + answer['StateAbr']);
      $("#cost").append("estimated cost: " + answer['TotalCost']);
    });
  } else {
    $('#cost').empty();
    $('#state').empty();
    $("#cost").append("estimated cost: 0, Need something in your cart");
  }
});

//                                                                               
//                                                                               
//                                                                 tttt          
//                                                              ttt:::t          
//                                                              t:::::t          
//                                                              t:::::t          
//      ssssssssss      ooooooooooo   rrrrr   rrrrrrrrr   ttttttt:::::ttttttt    
//    ss::::::::::s   oo:::::::::::oo r::::rrr:::::::::r  t:::::::::::::::::t    
//  ss:::::::::::::s o:::::::::::::::or:::::::::::::::::r t:::::::::::::::::t    
//  s::::::ssss:::::so:::::ooooo:::::orr::::::rrrrr::::::rtttttt:::::::tttttt    
//   s:::::s  ssssss o::::o     o::::o r:::::r     r:::::r      t:::::t          
//     s::::::s      o::::o     o::::o r:::::r     rrrrrrr      t:::::t          
//        s::::::s   o::::o     o::::o r:::::r                  t:::::t          
//  ssssss   s:::::s o::::o     o::::o r:::::r                  t:::::t    tttttt
//  s:::::ssss::::::so:::::ooooo:::::o r:::::r                  t::::::tttt:::::t
//  s::::::::::::::s o:::::::::::::::o r:::::r                  tt::::::::::::::t
//   s:::::::::::ss   oo:::::::::::oo  r:::::r                    tt:::::::::::tt
//    sssssssssss       ooooooooooo    rrrrrrr                      ttttttttttt  
//                                                                               
//                                                                               
//                                                                               
//                                                                               
//                                                                               
//                                                                               
//          

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