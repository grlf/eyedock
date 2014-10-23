var InsertPagesetterCommand=function(){
        //create our own command, we dont want to use the FCKDialogCommand because it uses the default fck layout and not our own
};
InsertPagesetterCommand.prototype.Execute=function(){
}
InsertPagesetterCommand.GetState=function() {
        return FCK_TRISTATE_OFF; //we dont want the button to be toggled
}
InsertPagesetterCommand.Execute=function() {

oEditor = FCK.Name;

        //open a popup window when the button is clicked
        //window.open('/eyedock_dev/index.php?module=pagesetter&func=pubfind&url=relative&html=a&targetID='+oEditor, 'insertPagesetter', 'width=750,height=350,scrollbars=yes,scrolling=yes,location=no,toolbar=no');
        
        window.open("http://www.eyedock.com/dev/index.php?module=pagesetter&func=pubfind&url=relative&html=a&targetID="+oEditor+"&targetMode=FCK", "", "width=750,height=315,resizable");
}
FCKCommands.RegisterCommand('pagesetter', InsertPagesetterCommand ); //otherwise our command will not be found
var oInsertPagesetter = new FCKToolbarButton('pagesetter', FCKLang.InsertLinkBtn);
oInsertPagesetter.IconPath =  FCKPlugins.Items['pagesetter'].Path + 'pagesetter.gif' ; //specifies the image used in the toolbar
FCKToolbarItems.RegisterItem( 'pagesetter', oInsertPagesetter );