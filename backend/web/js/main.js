/* 
 * Project Name: eulims * 
 * Copyright(C)2018 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 01 31, 18 , 1:42:50 PM * 
 * Module: main * 
 */
jQuery(document).ready(function ($) {
    $('.btn-modal').click(function () {
        // alert(this.value);
        LoadModal(this.name, this.value);
    });

    // --- Delete action (bootbox) ---
    yii.confirm = function (message, ok, cancel) {
        var title = $(this).data("title");
        var confirm_label = $(this).data("ok");
        var cancel_label = $(this).data("cancel");
        
        bootbox.confirm(
            {
                title: title,
                message: message,
                buttons: {
                    confirm: {
                        label: confirm_label,
                        className: 'btn-danger btn-flat'
                    },
                    cancel: {
                        label: cancel_label,
                        className: 'btn-default btn-flat'
                    }
                },
                callback: function (confirmed) {
                    if (confirmed) {
                        !ok || ok();
                    } else {
                        !cancel || cancel();
                    }
                }
            }
        );
        // confirm will always return false on the first call
        // to cancel click handler
        return false;
    };
    $("#modalButton").click(function(){
        $("#modal").modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
    //modalCancel
    $("#modalCancel").click(function(){
        $("#modal").modal('hide');
    });
});

function MessageBox(){
    krajeeDialog.dialog(
        'This is a <b>custom dialog</b>. The dialog box is <em>draggable</em> by default and <em>closable</em> ' +
        '(try it). Note that the Ok and Cancel buttons will do nothing here until you write the relevant JS code ' +
        'for the buttons within "options". Exit the dialog by clicking the cross icon on the top right.',
        function (result) {alert(result);}
    );
}
function ConfirmBox(Title, Message){
    bootbox.confirm(
        {
            title: Title,
            message: Message,
            buttons: {
                confirm: {
                        label: 'Yes',
                        className: 'btn-danger btn-flat'
                },
                cancel: {
                        label: 'No',
                        className: 'btn-default btn-flat'
                }
            },
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
}
function LoadModal(header,url,closebutton){
    if(closebutton==undefined){
        closebutton=true;
    }
    var content='<div id="boot-box-content" style="padding-bottom: 30px"><i class="fa fa-spin fa-spinner"></i> Loading...</div>';
    var dialog = bootbox.dialog({
        title: header,
        message: content,
        closeButton: closebutton
    });
    
    dialog.init(function(){
        setTimeout(function(){
            dialog.find('#boot-box-content').load(url);
        }, 100);
    });
  
  }
function printPartOfPage(elementId) {
    var printContent = document.getElementById(elementId);
    var windowUrl = 'about:blank';
    var uniqueName = new Date();
    var windowName = 'Print' + uniqueName.getTime();
    var printWindow = window.open(windowUrl, windowName, 'left=50000,top=50000,width=0,height=0');

    printWindow.document.write(printContent.innerHTML);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}