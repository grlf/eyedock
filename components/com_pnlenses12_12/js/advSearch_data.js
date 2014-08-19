
var searchOptionsObj = new Array(
	{name: "Even more options...", id: "" },
    {name: "Name", id: "phrase", input: "text"}, 
    {name: "CL power", id: "clRx",  input: "text"}, 
    {name: "Refraction", id: "refraction",  input: "text"}, 
    {name: "Company", id: "company", input: "html"}, 
    {name: "Sphere power", id: "sph", input: "select", suffix:"(or more)"}, 
    {name: "Base Curve", id: "bc",  input: "select"},    
    {name: "Diameter", id: "diam", operator: "moreless",input: "select"}, 
    {name: "Polymer", id: "polymer", input: "html"}, 
    {name: "dk", id: "dk", operator: "moreless",input: "select"},
    {name: "Water content", id: "h2o", operator: "moreless",input: "select"}, 
    {name: "Center thickness", id: "ct", operator: "moreless",input: "select"}, 
    {name: "Optic zone", id: "oz", operator: "moreless", input: "select"},
    {name: "Disposable", id: "disposable", input: "bool"},
    {name: "Toric: Cyl power", id: "cylinder", input: "select", suffix: "(or more)"}, 
    {name: "Toric: Oblique axis", id: "oblique",  input: "bool"},
    {name: "Toric: Axis steps", id: "axis_steps",  input: "select", suffix:"(or less)"},
    {name: "Bifocal: Add power", id: "max_add",  input: "select", suffix: "(or more)"},
    {name: "Bifocal: Type", id: "bifocal_type",  input: "select"},
    {name: "Cosmetic: Enhancer color", id: "colors_enh", input: "select"},
    {name: "Cosmetic: Opaque color", id: "colors_opq", input: "select"},
    {name: "Visibility tint?", id: "visi",  input: "bool"},
    {name: "Extended wear?", id: "ew",  input: "bool"},
    {name: "Novelty lens?", id: "novelty",  input: "bool"}
    //,  {name: "Prosthetic lens?", id: "prosthetic",  input: "bool"}
    //,{name: "Replacement", id: "replacement", operator: "moreless", input: "html"}   
);

var polymer_html = "";

var company_html = "";

jQuery(document).ready(function() {
	jQuery.ajax({
        type: "POST",
        url: componentURL + "&task=dataList&search=polymer&format=raw",
        dataType: "html",
        success: function(result){
			polymer_html = result;
        }
    });

	jQuery.ajax({
        type: "POST",
        url: componentURL + "&task=dataList&search=company&format=raw",
        dataType: "html",
        success: function(result){
			company_html = result;
        }
    });
	
});

//a list of all the operators lists. the values refer to a variable of the same name
//var operators = new Array ("moreless", "yesno", "idis", "comesin");

var moreless = new Array (
	{name:">=", value:""},
	{name: "<=", value:"-"}
	);
	

var diam_options = new Array("any", "10.0 mm", "11.0 mm" , "12 mm", "13.0 mm", "13.5 mm", "14.0 mm", "14.2 mm", "14.4 mm", "14.5 mm", "15.0 mm", "15.5 mm", "16.0 mm", "17.0 mm", "18.0 mm", "20.0 mm" );
//var diameter_selected = 4; 

var sph_options = new Array("+20.00 D", "+19.00 D", "+18.00 D", "+17.00 D", "+16.00 D", "+15.00 D", "+14.00 D", "+13.00 D", "+12.00 D", "+11.00 D", "+10.00 D", "+9.00 D", "+8.00 D", "+7.00 D", "+6.00 D", "+5.00 D", "+4.00 D", "+3.00 D", "+2.00 D", "+1.00 D", "any", "-1.00 D", "-2.00 D", "-3.00 D", "-4.00 D", "-5.00 D", "-6.00 D", "-7.00 D", "-8.00 D", "-9.00 D", "-10.00 D", "-11.00 D", "-12.00 D",  "-13.00 D", "-24.00 D", "-15.00 D", "-16.00 D", "-17.00 D", "-18.00 D", "-19.00 D", "-20.00 D", "-25.00 D");
var sph_selected = 20; 

var dk_options = new Array("any", 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 125, 150);
//var dk_selected = 4; 

var oz_options = new Array("any", "5 mm", "6 mm", "7 mm", "8 mm", "9 mm", "10 mm" , "11 mm", "12 mm");
//var oz_selected = 2;

var ct_options = new Array("any", "5 mm", "6 mm", "7 mm", "8 mm", "9 mm", "10 mm" , "11 mm", "12 mm");
//var ct_selected = 2;

var ct_options = new Array("any", ".04 mm", ".05 mm", ".06 mm", ".07 mm", ".08 mm", ".09 mm" , ".10 mm", ".11 mm");
//var ct_selected = 2;

var h2o_options = new Array("any", "20%", "30%", "40%", "50%", "60%", "70%" , "80%");
//var h2o_selected = 2;

var axis_steps_options = new Array("any", "10&deg;", "5&deg;", "1&deg;");
//var axis_steps_selected = 2;

var bc_options = new Array("any", "flat", "median", "steep");


// = new Array("daily", "2 weeks", "monthly", "quarterly", "6 months", "yearly");

var colors_enh_options = new Array("any", "Aqua", "amber", "Blue", "Brown", "Gray", "Green", "Honey", "Yellow", "Violet");
var colors_opq_options = new Array("any", "Aqua", "amber", "Blue", "Brown", "Gray", "Green", "Honey", "Yellow", "Violet");

var cylinder_options = new Array( "any", "-0.75 D", "-1.25 D", "-1.75 D", "-2.25 D", "-2.75 D", "-3.25 D", "-3.75 D", "-4.00 D", "-5.00 D", "-6.00 D", "-7.00 D", "-8.00 D",  "-9.00 D", "-10.00 D");

var max_add_options = new Array( "any", "+1.00 D", "+1.50 D", "+2.00 D", "+2.50 D", "+3.00 D", "+3.50 D", "+4.00 D", "+5.00 D");

var bifocal_type_options = new Array ("any", "aspheric", "aspheric back surface", "aspheric front surface", "concentric zones", "concentric, distance center", "concentric, near center", "diffractive optics" ,"monovision" ,"progressive" ,"translating" ,"other");

//var polymer_options =

// jQuery.ajax({url:"http://www.eyedock.com/api_new?",success:function(result){
// 	alert (result);
// }});
