function aLens()
{
   this.power="";
   this.bc="";
   this.diameter="";
   this.perif="";
   this.oz="";
   this.other="";
}

function choice()
{
   this.msg="";
   this.address="";
   this.aspheric=0;
  // this.prism=0;
   this.thin=0;
   this.front=0;
   this.back=0;
}

//__________variables__________

var MR=new Specs();
var Cornea=new Kcurve();
var sRx=new rx();
var theK=new fullK();
var specArray=new Array(0,0,0);
var kArray=new Array(0,0,0);
var Lens=new aLens();
var Search=new choice();

var vertexDist=12;
var vertexed=0;   //you only want to vertex once!
var specReady=0;
var kReady=0;
var isChecked=true;



//___________form tools____________

function changeRound()
{
   isChecked=document.theForm.round.checked;
}

function replaceImage (anImgName,anImageFile)
{
   document.images[anImgName].src=anImageFile;
}

function focusThing(x)
{

      if (x=="spec") msg="Enter a glasses prescription in plus or minus cyl.";

      if (x=="k")msg="Enter the keratometry measurements in this format: 45/43.25@90.<br/><br/>You can use the '*' key instead of the '@' key if you want.<br/><br/>(The keratometry measurement is optional for soft lens calculations.)";

      if (x=="calc") msg="Select which type of lens you want to calculate and then hit the 'calculate' button.<br/><br/>If you're not sure, select 'advice'.";

      myTarget=document.getElementById("theComment");
      myTarget.innerHTML="<font color='darkblue'><br/><br/>"+msg+"</font>";
      myTarget.style.backgroundColor="transparent";

}

function displayAnswer(thoughts)
{
   answer="<h3><u>Suggested Lens:</u></h3>";
   answer+="<b>Power:</b>  "+Lens.power;
   answer+="<br/><b>Base curve:</b>  "+Lens.bc;
   answer+="<br/><b>Diameter:</b>  "+Lens.diameter;
   if (Lens.oz!="") answer+="<br/><b>Optic Zone:</b>  "+Lens.oz;
   if (Lens.perif!="") answer+="<br/><b>Peripheral curves:</b><br/>    "+Lens.perif;
   if (Lens.other!="") answer+=Lens.other;

   answer+="<br/><br/>Some thoughts...<br/><br/>";
   answer+=thoughts+"<br/><br/>";

   if (Search.msg!="")
   {
      answer+=Search.msg;

      // answer+="<form name='form1'>";
     

      //alert (answer);
    }

   myTarget=document.getElementById("theComment");
   myTarget.innerHTML=answer;
   myTarget.style.backgroundColor="#CCCCFF";

}

/*
function findLenses()
{
   place="../ContactDB/advCLlist.php?"+Search.address;

   if (document.theForm.rep[1].checked) place+="&replace=6months";

   if (document.theForm.rep[2].checked) place+="&replace=year&replace_greater=longer";

   //if (Search.prism==1 && document.theForm.prism.checked) place+="&toricType=prism ballast";

   if (Search.thin==1 && document.theForm.thin.checked) place +="&toricType=thin zone";

   //if (Search.front==1 && document.theForm.front.checked) place+="&front=1";

   //if (Search.back==1 && document.theForm.back.checked) place+="&back=1";

   listWin=window.open(place,'listWin','scrollbars=yes,toolbar=yes,width=500,height=400,resizable=yes');
   listWin.moveTo(0,0);
   listWin.focus();


}
*/

function clearAnswer(what)
{


   if (what=="mr")
   {
      vertexed=0;
      specReady=0;
      specArray=[0,0,0];
      MR.sph=0;MR.cyl=0,MR.axis=0;
   }

   if (what=="k")
   {
      kReady=0;
      kArray=[0,0,0];
      Cornea.k1=0;Cornea.k2=0;Cornea.k2meridian=0;
   }

   if (what=="v")
   {
     vertexed=0;
     var e = document.theForm.vertexDist;
     vertexDist = parseInt(e.options[e.selectedIndex].text);
      //vertexDist=document.theForm.vertexDist.options[selectedIndex].value;
      //MR.sph=0;MR.cyl=0,MR.axis=0;
   }

}

function clearForm()
{
   vertexed=0;
   specReady=0;
   specArray=[0,0,0];
   kReady=0;
   kArray=[0,0,0];

   isChecked=true;
   vertexDist=12;

   MR.sph=0,MR.cyl=0,MR.axis=0;
      Cornea.k1=0;Cornea.k2=0;CorneaK2meridian=0;
   Lens.power="";Lens.bc="";Lens.diameter="";Lens.perif="";Lens.oz="";Lens.other="";
   Search.msg="";Search.address="";Search.aspheric=0;Search.thin=0;

   document.theForm.kBox.value="";
   document.theForm.rxBox.value="";
   document.theForm.round.checked=true;
   document.theForm.vertexDist.selectedIndex=5;
   document.theForm.philosophy.selectedIndex=0;
   document.theForm.calcType.selectedIndex=0;

   focusThing("spec");




}


//_____________verification tools________________

function submitRx(prescription)
{
   var rxNumsOnly=sRx.onlyNums(prescription);

   if (!sRx.validateFormat(rxNumsOnly))
   {
      alert("Please enter the prescription in this format: -10.25+2.50x130.  Plus or minus cyl is accepable");

      specReady=0;
   } else {

      specArray=sRx.stringBreaker(rxNumsOnly);

      var comment =sRx.validateNumbers(specArray);
      if (comment !="")
      {
         alert (comment);
      } else {

         specReady=1;

         MR.sph=specArray[0];
         MR.cyl=specArray[1];
         MR.axis=specArray[2];

         document.theForm.rxBox.value=sRx.prettyString(specArray,isChecked);


      }
   }
}

function submitK(kString)
{
   kString=theK.onlyNums(kString);
   if (!theK.validateFormat(kString))
   {
     alert ("You need to enter the keratometry value in this format: 43.25/44.50 @ 87");
     kReady=0;
   }  else {

     var newArray=theK.stringBreaker(kString);
     if (!theK.verifyRange(newArray))
     {
        alert ("Keratometry readings must be between 32.00 and 75.00 Diopters with an axis between 0 and 180 degrees");
        kReady=0;
     } else {

        kReady=1;
        kArray=newArray;

        Cornea.k1=kArray[0];
        Cornea.k2=kArray[1];
        Cornea.k2meridian=kArray[2];

        document.theForm.kBox.value=theK.prettyString(kArray,isChecked);


     }
   }
}


//_______calculation functions________

function calcReady (calcType)
{
   var alertCom="";
   var trans="  A ";

   //may be redundant but works well when browse backwards to page
   //submitRx(document.theForm.rxBox.value);
   //if (calcType>2) submitK(document.theForm.kBox.value);

   if (specReady==0)
   {
   alertCom += " You haven't entered a valid spectacle prescription yet.";
   trans="  In addition, a "
   }

   if (kReady==0 && calcType>2) alertCom += trans+"valid keratometric measurement is needed to do this calculation.";

   if (kReady==0 && calcType<3) document.theForm.kBox.value="";


   if (alertCom=="")
   {  return true;
   } else {
   alert (alertCom)
   return false;
   }
}

function startCalc()
{

  var selIndex=document.theForm.calcType.selectedIndex;

  Search.address="";
  Search.msg="";
  Search.thin=0;
  Search.prism=0;
  Search.aspheric=0;


  if (calcReady(selIndex))
  {
    if (vertexed==0)
    {
       MR.corneaPlane(vertexDist);
       vertexed=1;
    }

   switch (selIndex)
   {

     case 0:  //RGP advice
        suggest();
     break;

     case 1:  //soft sphere
        softSph();
     break;

     case 2:  //soft toric
        softToric();
     break;

     case 3:  //RGP sph
         RGPsph();
     break;

     case 4:   //RGP bitoric
        RGPbitoric();
     break;

     case 5:  //RGP back toric
        RGPbackToric();
     break;

     case 6:  //RGP front toric
        RGPfrontToric();
     break;



   }
  }
}

function softBCrecommend()
{
   if (Cornea.midK()<=42) return"flat";

   if (Cornea.midK()>42 && Cornea.midK()<=43) return"flat to median";

   if (Cornea.midK()> 43 && Cornea.midK()<=44) return "median";

   if (Cornea.midK()>44 && Cornea.midK()<=45) return "median to steep";

   if (Cornea.midK()>45) return"steep";
}


function softSph()
{
   var thoughts="";
   var pwrArray=new Array(MR.sphEquiv(),0,0);

   Lens.power=sRx.prettyString(pwrArray,isChecked);
   Lens.diameter="usually btwn 14-15";
   Lens.perif="";
   Lens.oz="";
   Lens.other="";

   if (kReady!=0)
   {
      Lens.bc=softBCrecommend();
   } else {
      Lens.bc="<br/>depends on keratometry";
   }

    percentCyl=Math.abs(MR.refractiveCyl()/MR.sphEquiv() );


   if (MR.refractiveCyl()>.6)
   {
      thoughts+="With "+rounder(MR.cyl)+" Diopters of refractive astigmatism (at the corneal plane), a toric lens may be a better choice for this patient.";

      if (percentCyl<.25) thoughts+="<br/><br/>...Then again, because the cylinder is such a small percentage of the overall prescription, it may be tolerated.";

      if (percentCyl>.3) thoughts+="  This is especially true as the refractive cylinder is such a significant percentage of the overall prescription.";

   }   else {

       if (MR.refractiveCyl()>.37)
       {
          thoughts+="<br/>  You may also want to consider an aspheric spherical design - it may mask the small amount of residual astigmatism.";
       } else {

         thoughts+="With only "+rounder(MR.cyl)+ " Diopters of refractive astigmatism (at the corneal plane), a spherical lens should work well for this patient.";
      }
   }


if (Math.abs(MR.sphEquiv())>9)
   {
      Search.msg=" Your options are more limited with this magnitude of power.  Click the following link to see lenses that are available in this range.";

   } else {
      Search.msg="  You shouldn't need much help finding lenses in this power range but if you want some, click the following link."
   }
     
    Search.msg += "<p><a href='http://www.eyedock.com/index.php?option=com_pnlenses#view=list&clRx%5B%5D=" + encodeURIComponent(Lens.power ) + "'>Search for CLs</a></p>";

     //alert (answer);
     myTarget=document.getElementById("hidden_content");
   //myTarget.innerHTML=answer;
   //Search.address="power="+signNum(rounder(MR.sphEquiv()))+"&sphere="+twoDec(MR.sphEquiv());


   displayAnswer (thoughts);
}

function softToric()
{
   var thoughts="";

   Lens.power=MR.prettyString("-",isChecked);
   Lens.diameter="usually btwn 14-15";
   Lens.perif="";
   Lens.oz="";
   Lens.other="";


   if (kReady!=0)
      {
         Lens.bc=softBCrecommend();
      } else {
         Lens.bc="<br/>depends on keratometry";
   }

   if (MR.refractiveCyl()>.6)
      {
         thoughts+="With "+rounder(MR.cyl)+" Diopters of refractive astigmatism (at the corneal plane), a toric lens is a good choice for this patient.";
      }   else {

         thoughts+="With "+rounder(MR.cyl)+ " Diopters of refractive astigmatism (at the corneal plane), a spherical lens may work nearly as well for this patient.";

         if (MR.refractiveCyl()>.37) thoughts+="<br/>  You may also want to consider an aspheric spherical design - it may mask the small amount of residual astigmatism.";
       }

   if (kReady!=0 && Cornea.cylType()=="ATR")
   {
       thoughts+="<br/><br/>  Consider a thin zone lens: They work best on against-the-rule corneas";
       if (MR.sph<-5) thoughts+=" and in higher myopes";
       thoughts+=", like this patient.";

       Search.thin=1;
   }


   if (MR.axisType()=="oblique") thoughts +="<br/><br/>  Oblique toric hydrogels are often accused of not stabilizing well.  They've gotten much better, but if you have trouble consider trying a larger diameter lens to improve rotation.";

   if (kReady!=0 && Cornea.cornealCyl()<1.25)
   {
      thoughts+="<br/><br/>  One more thing to consider: In theory, <i>front</i> toric hydrogels are considered to be the most stable design on corneas with low amounts of toricity (like this one).";
      Search.front=1;
   }

   if (Cornea.cornealCyl()>2)
   {
      thoughts+="<br/><br/>  One more thing to consider:  In theory, <i>back</i> toric hydrogels are more stable on corneas with higher amounts of toricity (like this one).";
      Search.back=1;
   }

   if (Math.abs(MR.sph)>6 || Math.abs(MR.cyl)>2.25)
   {
      Search.msg="  Your options become more limited ";

      if (Math.abs(MR.cyl)>2.25)
      {
         Search.msg+=" when you're looking for lenses to correct this much astigmatism.  C"
      } else {
         Search.msg+=" when you're looking for toric lenses that also need to correct this much sphere power.  C"
      }
   } else {

       Search.msg="  You shouldn't have much difficulty finding a toric lens in these powers, but c";

   }

     Search.msg+="licking the link below will help you find some options.";
     
     Search.msg += "<p><a href='http://www.eyedock.com/index.php?option=com_pnlenses#view=list&clRx%5B%5D=" + encodeURIComponent(Lens.power ) + "'>Search for CLs</a></p>";

     
     //alert (answer);
     myTarget=document.getElementById("hidden_content");
   //myTarget.innerHTML=answer;

      /*Search.address="power="+Lens.power+"&sphere="+twoDec(MR.minusCylsph())+"&cylPower="+twoDec(MR.minusCylcyl())+"&toric=true";

    
     if ((MR.axis>25&&MR.axis<65)||(MR.axis>115&&MR.axis<155))
     {
        if ((MR.axis>35&&MR.axis<55)||(MR.axis>125&&MR.axis<145))
        {
           Search.address+="&cylAxis=45";
        } else {
           Search.address+="&cylAxis=30";
        }

     }
     */

   displayAnswer(thoughts);
}


function RGPsph()
{
   if (document.theForm.philosophy.selectedIndex==1)
   {  //_____Bennet & Henry method____

      OAD=="9.0 - 9.2";
      OZ=="7.8 - 8.0";
      x=Cornea.cornealCyl();
      BC=Cornea.flatK();

      if (x<=.5) BC-=.75;
      if (x>.5 && x<=1) BC-=.5;
      if (x>1 && x<=1.25) BC-=.25;
      if (x>1.75 && x<=2) BC=(BC*1)+.25;
      if (x>2 && x<=3) BC=(BC*1)+.50;
      if (x>3) BC=(1*BC)+.75;

      periph= twoDec((337.5/BC)+.8);
      periph1=twoDec((periph*1)+1);
      periph2=twoDec((periph1*1)+1.4);

      periph+="/.3mm, ";
      periph1+="/0.2mm";
      periph2+="/0.2mm";


   } else {   //________ICO mid-K_______


      BC=Cornea.midK();

      if (Cornea.flatK()>45.5)
      {
         OAD="8.5";
         OZ="7.3";
         periph2="10.50/0.4mm";

      } else {

         if (Cornea.flatK()<41.5)
         {
            BC=BC-1;
            OAD="9.5";
            OZ="8.3";
            periph2="12.25/0.4mm";

         }  else {
            BC=BC-.5;
            OAD="9.0";
            OZ="7.8";
            periph2="11.25/.4mm";
         }
      }

      periph="";
      periph1=twoDec((337.5/BC)+1);
      periph1+="/.2mm";


   } //___end of ICO and B&H____

   power=(Math.max(MR.hPwr(),MR.vPwr()))+(Cornea.flatK()-BC);

   if (isChecked) power=rounder(power);
   if (isChecked) BC=rounder(BC);
   Kmm=twoDec(337.5/BC);

   Lens.power=signNum(power);
   Lens.bc= Kmm+" ("+ twoDec(BC)+")";
   Lens.diameter=OAD;
   Lens.perif=periph+periph1+", "+periph2;
   Lens.oz=OZ;
   Lens.other="";


   resid=residAstig();

   thoughts="";

   if (Cornea.cornealCyl()>2.75)
   {
      thoughts+="With "+twoDec(Cornea.cornealCyl())+" Diopters of corneal astigmatism, you may want to consider a toric lens.";
   } else {
      if (MR.refractiveCyl()>3)
      {
         thoughts+="This degree of spectacle astigmatism may be better corrected with a toric lens.";
      } else {

         thoughts+="A spherical RGP would leave about "+resid+" D of residual astigmatism.";

        if (resid>1.25)
        {
           thoughts+="  Unless your patient tolerates blur well, this is going to be unacceptable.  Try a toric lens instead.";
        } else if (resid==0) {
           thoughts+="  This lens should provide great vision!";

        } else {
          if (residType()=="ATR" || residType()=="oblique")
          {
             if (resid<=.5)
             {
                thoughts+="  Although it is "+residType()+" astigmatism, the low amount should be tolerated well by the patient."
             } else {
                thoughts+="  Sometimes this much "+residType()+" astigmatism isn't tolerated and a toric lens should be considered."
             }
           } else {
              if (resid<1)
        {
           thoughts+="  Being a fairly low magnitude and because it's  "+residType()+" astigmatism, the patient will likely tolerate it well."
         } else {
           thoughts+="  This much "+residType()+" astigmatism may not be tolerated and a toric lens should be considered."
               }

           }
         }
      }
   }




   displayAnswer(thoughts);
}


function RGPbitoric()
{

   thoughts="";

   if (Cornea.cornealCyl()<4.25) fitfactor=1;
   if (Cornea.cornealCyl()<3.75) fitfactor=.75;
   if (Cornea.cornealCyl()<2.75) fitfactor=.5;
   if (Cornea.cornealCyl()>=4.25) fitfactor=1.25;

   BC1=Cornea.flatK()-.25;
   BC2=Cornea.steepK()-fitfactor;


   //determine which power is closer to each k meridian
   if (Cornea.flatK()==Cornea.hPwr())
   {
      FP=MR.hPwr()+(1*.25);
      SP=MR.vPwr()+(1*fitfactor);
   } else {
      FP=MR.vPwr()+(1*.25);
      SP=MR.hPwr()+(1*fitfactor);
   }

    Lens.power=signNum(rounder(FP))+" / "+signNum(rounder(SP));
    Lens.diameter="usually btwn 8.8-9.8";
    Lens.bc="<br/>"+twoDec(337.5/BC1) +" / "+twoDec(337.5/BC2)+"     ("+twoDec(rounder(BC1))+" / "+twoDec(rounder(BC2))+")";
    Lens.Perif="";
    Lens.oz="";
    Lens.other="";

    if (Cornea.cornealCyl()<2)
    {
    thoughts+="With only "+twoDec(Cornea.cornealCyl())+" Diopters of corneal cylinder a bitoric lens may not be the best option for this patient.  They usually need at <i>least</i> 2D of corneal toricity  so the back surface of the lens can align with the proper meridians.";
    } else {
       if (Cornea.cornealCyl()<3)
       {
          thoughts+="With "+twoDec(Cornea.cornealCyl())+" Diopters of corneal cylinder, a bitoric lens is an aceptable choice for this patient.  However, some practitioners feel that a bitoric shouldn't be considered unless the cyl is at least 2.50 D and sometimes even 3 D.";
       } else {

          thoughts+="With "+twoDec(Cornea.cornealCyl())+" Diopters of corneal cylinder, a bitoric lens is a good choice for this patient.";
       }
    }

    displayAnswer(thoughts);

}

function RGPbackToric()
{
   BCspan=MR.refractiveCyl()*.66;
   thoughts="";

   //determine which spec pwr is on flat k meridian
   if (Math.abs(Cornea.flatKmeridian()-MR.hAxis())<Math.abs(Cornea.flatKmeridian()-MR.vAxis()))
   {
      FP=MR.hPwr();
   } else {
      FP=MR.vPwr();
   }

  BC1=337.5/Cornea.flatK();
  BC2=337.5/(Cornea.flatK()*1+1*BCspan);
  BCavg=BC1/2+BC2/2;

   Lens.power=signNum(rounder(FP));
   Lens.diameter="Usually btwn 8.8 - 9.8";
   Lens.bc="<br/>"+twoDec(BC1)+" / "+twoDec(BC2)+"  ("+twoDec(rounder(337.5/BC1))+" / "+twoDec(rounder(337.5/BC2))+")";



   if (Math.abs(BC1-BC2)>.7)
   {
      Lens.perif=twoDec(BC1+1)+"/"+twoDec(BC2+1)+"   "+twoDec(BC1+3)+"/"+twoDec(BC2+3);
   }  else {
      Lens.perif=twoDec(BCavg*1+1)+"/.3  "+twoDec(BCavg*1+3)+"/.3";
   }

   Lens.oz="";
   Lens.other="";

   if (Cornea.cylType()=="WTR" || Cornea.cylType()=="oblique")
   {
      if (MR.refractiveCyl()>=Cornea.cornealCyl() )
      {
         thoughts+="Back toric lenses work well in situations like this where there is more refractive cylinder than corneal cylinder. <br/><br/>However, "
      } else {
         thoughts+="In this patient, the refractive cylinder is less than the corneal cylinder, which isn't the ideal situation in a back toric lens.  <br/>In addition, ";
      }

      thoughts+="back toric lenses work best on against-the-rule corneas, which may be a consideration as this cornea is "+Cornea.cylType()+".";

      if (Cornea.cornealCyl()<1.5)
      {
         thoughts+="<br/><br/>  There's one more consideration: Back toric lenses are usually reserved for cases where there <i>at least</i> 1.50 D of corneal cyl.  This cornea only has "+twoDec(Cornea.cornealCyl())+" D.";
      }
   }

   if (Cornea.cylType()=="ATR")
   {
      if (MR.refractiveCyl() >=  Cornea.cornealCyl())
      {
         if (Cornea.cornealCyl()>1.50)
         {
            thoughts+="With an ATR cornea and "+ twoDec(Cornea.cornealCyl())+  " D of corneal astigmatism, a back toric is an acceptable choice for this patient.";

            axisDiff=Math.abs(Cornea.horizK()-MR.hAxis());
            if (axisDiff>20)
            {
               thoughts+="<br/><br/>One potential problem is that the refractive axis is "+parseInt(axisDiff)+"&deg; away from the corneal axis.  These lenses usually work best when the axis are closer";
            }

         } else {

            thoughts+=twoDec(Cornea.cornealCyl())+" D is usually too little corneal astigmatism for a back toric lens.";
         }
      } else {
         //if refractive cyl not > corneal cyl
         //alert (Cornea.cornealCyl());
         //alert (MR.refractiveCyl());
         thoughts+="Back toric lenses work their best when the refractive cylinder is greater than the corneal cylinder, which is not the case in this patient.";

         if (Cornea.cornealCyl()>2.25)
         {
            thoughts+="<br/>You may want to consider a bitoric lens instead.";
         }
      }
  }

  if (Math.abs(BC1-BC2)>.7) thoughts+="<br/><br/>  Because of the large amount of astigmatism, toric peripheral curves have been recommended to improve stability.";

  thoughts+="<br/><br/>*Remember that a back toric RGP induces cylinder in situ. Before you measure the lens on the lensometer you should calculate what the lens will measure in air.";



   displayAnswer(thoughts);

}


function RGPfrontToric()
{
   thoughts="";

   BC=Cornea.flatK();
   x=Cornea.cornealCyl()
   lensPwr=new Specs();


   if (x<.12) BC-=.5;
   if (x>=.12 && x<.37) BC-=.25;
   if (x>.67 && x<1.12) BC=BC*1+.25;
   if (x>=1.12 && x <1.67) BC=BC*1+.45;
   if (x>=1.67) BC=BC*1+.62;

   //calculate horiz & vert meridians
   mh=Cornea.hPwr()-BC+MR.hPwr();
   mv=Cornea.vPwr()-BC+MR.vPwr();

   //alert (residAstig());

   //put into plus cyl format
   if (mh>mv)
   {
      lensPwr.sph=mv;
      lensPwr.cyl=mh-mv;
      lensPwr.axis=MR.vAxis();
   } else {
      lensPwr.sph=mh;
      lensPwr.cyl=mv-mh;
      lensPwr.axis=MR.hAxis();
   }



   //determine prism power
   if (lensPwr.sphEquiv()>=0)
   {
     if (lensPwr.sphEquiv()>2)
     { prism="1.25"; } else {prism="1.00";}
   } else {
     if (lensPwr.sphEquiv()<2)
     {prism="1.50"; } else { prism="1.25";}
   }

   //determine axis after rotation compensation
   ODaxis=lensPwr.axis-12;
   OSaxis=lensPwr.axis*1+12;
   if (ODaxis>180) ODaxis-=180;
   if (ODaxis<0) ODaxis=ODaxis*1+180
   if (OSaxis>180) OSaxis-=180;
   if (OSaxis<0) OSaxis=ODaxis*1+180
   lensPwr.axis=ODaxis;
   powerOD=lensPwr.prettyString("+",isChecked)
   lensPwr.axis=OSaxis;
   powerOS=lensPwr.prettyString("+",isChecked)


   Lens.other="<br/><b>Prism:</b><br/>"+ prism+" p.d. axis 102&deg;(OD) or 78&deg;(OS)";

   Lens.power="<br/>for OD lens: "+powerOD+"<br/>for OS lens: "+powerOS+"<br/>(axis adjusted for 12&deg; nasal rotation)";
   Lens.diameter="Usually btwn 9.0-10.0";
   Lens.bc=twoDec(337.5/BC)+" ("+twoDec(rounder(BC))+")";
   Lens.perif=twoDec(337.5/BC+1)+"/.3 , "+twoDec(337.5/BC+3)+"/.3";

   if (residAstig()>=1)
   {
      if (Cornea.cornealCyl()<=1)
      {
         thoughts+="Since this eye would have about "+residAstig()+" D of residual astigmatism with a <i>spherical</i> RGP and because there's only about "+twoDec(Cornea.cornealCyl())+" D of corneal cylinder, a front toric RGP is an appropriate choice for this patient."
       } else {
          thoughts+="This cornea has "+twoDec(Cornea.cornealCyl())+" D of corneal toricity, which is usually considered too much for a front toric lens.";

       }
   } else {
      thoughts+="Because this eye would only have about "+residAstig()+" D of residual astigmatism with a <i>spherical</i> lens, I wouldn't suggest using a front toric lens in this situation";

      if (Cornea.cornealCyl()>1) thoughts+="<br/>  Also, "+twoDec(Cornea.cornealCyl())+" D is usually too much corneal toricity for this type of lens.";
   }

   thoughts+="<br/><br/>This lens is designed to be stabilized with a prism ballast.  If it does not rotate correctly, the prism can be increased or it can be truncated.<br/><br/>Because it is difficult to achieve rotational stability with these lenses, it is usually recommended that you try a soft toric lens first (unless it's contraindicated).<br/><br/>I would recommend a material with a dk >50 as the prism makes these lenses thicker.  Also, avoid smaller diameter lenses as they tend to decenter inferiorly."

   displayAnswer(thoughts);
}


function suggest()
{
   x="<b>Here's some thoughts regarding this patient:</b><br/><br/>";

   //soft lenses
   if (MR.refractiveCyl()<.75)
   {
      x+="Since there'll only be about "+twoDec(MR.refractiveCyl())+" D of refractive astigmatism (after vertexing), a spherical soft lens may work well for this patient.";

      if (MR.refractiveCyl()>=.50) x+="  You may want to consider an aspheric lens - it may mask this small amount of uncorrected astigmatism.";

   } else {
      x+="A soft toric lens is always an option with "+twoDec(MR.refractiveCyl())+" D of refractive astigmatism (after vertexing)";

      if (MR.axisType()=="oblique")
      {
         x+=", although it can sometimes be tougher when the astigmatism is oblique.";
      } else {
        x+=".";
      }
   }


  if (kReady!=0)
  {
   //rigid lenses - spherical

   if (Cornea.cornealCyl()<3 && residAstig()<=1)
   {
         x+="<br/><br/>A spherical rigid gas permeable lens may work well";

         if (residAstig()<=.25)x+=" as the residual astigmatism would be insignificant.";

         if (residAstig()>.25 && residAstig()<.75) x+=" as there would only be about "+residAstig()+" D of residual astigmatism.";

         if (residAstig()>=.75)
         {
            x+=" if you think the patient will accept "+residAstig()+" D of "+residType()+" residual astigmatism.";
         }
   }

   if (Cornea.cornealCyl()>=3)
   {
      x+="<br/><br/>  A spherical RGP lens probably won't work because of the large (~"+twoDec(Cornea.cornealCyl())+") amount of corneal toricity.";

      if (residAstig()>=1) x+="  Even if it did, there would be about "+residAstig()+" D of residual astigmatism.";
   }

   if (Cornea.cornealCyl()<3 && residAstig()>1) x+="<br/><br/>  A spherical RGP lens would leave about "+residAstig()+ " D of residual astigmatism, which will probably be unacceptable.";


   //rigid lenses - toric

   if (residAstig()>1 && Cornea.cornealCyl()<1)
   {
     x+="<br/><br/>  This is a situation where a front toric lens could work: There is only a little bit ("+twoDec(Cornea.cornealCyl())+" D) of corneal toricity and a large amount ("+twoDec(MR.refractiveCyl())+ " D) of refractive astigmatism.  However, because it's hard to acheive rotational stability with these lenses, you may want to consider a soft toric lens first.";
   }

   if (Cornea.cornealCyl()>=2)
   {
      x+="<br/><br/>  When there is this much ("+twoDec(Cornea.cornealCyl())+" D) corneal toricity, a bitoric lens would be a good choice.";

      if (residAstig()>.75) x+="  In fact, because a spherical lens would leave "+residAstig()+" D of residual astigmatism, a bitoric lens would be most practitioner's first choice.";

   }

   if (residAstig()>.75 && Cornea.cornealCyl()>=1.5)
   {
      x+="<br/><br/>  A back toric sometimes works well when there's this much ("+twoDec(Cornea.cornealCyl())+" D) corneal toricity and there'd be "+residAstig()+" D of residual astigmatism with a spherical RGP.";

      if (Cornea.cylType()=="ATR")
      {
        x+="  It's also nice that this cornea is ATR, as back toric RGPs tend to work best in this situation.";
      } else {
        x+="  One negative is that this cornea is "+Cornea.cylType()+". Back torics work best on ATR corneas.";
      }


   }
  } else {  //if k info wasn't given
     x+="<br/><br/>  ...I cannot give any advice about RGP lenses without knowing the keratometry information."

  }

   myTarget=document.getElementById("theComment");
   myTarget.innerHTML=x;
   myTarget.style.backgroundColor="#CCCCFF";
}






//_____misc functions____

function rounder (number)
{
    if (isChecked==false) return number;
  return (Math.round(number*4))/4;
}

function signNum(number)
{
   sign="";
      if (number>0) sign="+";
   return sign+number.toFixed(2);
}

function twoDec(number)
{
   //alert (number);
   return number.toFixed(2);
}

function residAstig()
{
   hm=Cornea.hPwr()*1+MR.hPwr();
   vm=Cornea.vPwr()*1+MR.vPwr();
   return twoDec(rounder(Math.abs(hm-vm)));
}

function residType()
{
   if (Cornea.cornealCyl()>MR.refractiveCyl() )
   {
      return Cornea.cylType();
   } else {
      return MR.axisType();
   }

}
