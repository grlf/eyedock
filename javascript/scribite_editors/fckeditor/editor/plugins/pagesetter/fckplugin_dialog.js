// Register the related command.
// RegisterCommand takes the following arguments: CommandName, DialogCommand 
// FCKDialogCommand takes the following arguments: CommandName, Dialog Title, Path to HTML file, Width, Height 
 
FCKCommands.RegisterCommand( 'pagesetter', new FCKDialogCommand( 'pagesetter', FCKLang.InsertLinkDlgTitle,
FCKPlugins.Items['pagesetter'].Path + 'fck_pagesetter.html', 340, 200 ) ) ;
 
// Create the toolbar button.
// FCKToolbarButton takes the following arguments: CommandName, Button Caption
 
var oInsertLinkItem = new FCKToolbarButton( 'pagesetter', FCKLang.InsertLinkBtn ) ;
oInsertLinkItem.IconPath = FCKPlugins.Items['pagesetter'].Path + 'pagesetter.gif' ;
FCKToolbarItems.RegisterItem( 'pagesetter', oInsertLinkItem ) ;
 
// The object used for all InsertLink operations. 
var FCKInsertLink = new Object() ; 
 
// Add a new InsertLink at the actual selection.  
// This function will be called from the HTML file when the user clicks the OK button. 
// This function receives the values from the Dialog 
 
FCKInsertLink.Add = function( linkname, caption ) 
{ 
if(linkname.substr(0,4) != "http" && linkname.substr(0,4) != "HTTP") 
linkname = "http://"+linkname ; 
FCK.InsertHtml("<a href='"+linkname+"'>"+caption+"</a>") ; 
} 
//End code