$(".name").attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
     "data-parsley-maxlength" :"15",
     "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 15 ",
     "data-parsley-minlength" :"3",
     "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف"

});

$(".nameValid").attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
     "data-parsley-maxlength" :"15",
     "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 15 ",
     "data-parsley-minlength" :"3",
     "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف"

});

$('.number').attr({
     min:1,
    "data-parsley-type":"number",
    "data-parsley-type-message":"يجب الا يقل الرقم عن 1",
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",

});

$('.title').attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
    "data-parsley-maxlength" :"150",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 150 ",
    "data-parsley-minlength" :"3",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف"

});

$('.sub_title').attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
    "data-parsley-maxlength" :"200",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 200 ",
    "data-parsley-minlength" :"3",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف"

});

$('.email').attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
    "data-parsley-maxlength" :"75",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 75 ",
    "data-parsley-minlength" :"10",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 10 حروف",
    "data-parsley-type":"email",
    "data-parsley-type-message":"يجب ادخال صيغة بريد الكترونى صحيحة"

});

$('.url').attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
    "data-parsley-maxlength" :"200",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 200 ",
    "data-parsley-minlength" :"10",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 10 حروف",
    "data-parsley-type":"url",
    "data-parsley-type-message":"يجب ادخال صيغة رابط صحيحة"

});

$('.phoneCenter').attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
    "data-parsley-maxlength" :"25",
    "data-parsley-maxlength-message": "اقصى عدد للارقام مسموح به هو 25",
    "data-parsley-minlength" :"10",
    "data-parsley-minlength-message":"اقل عدد ارقام مسموح به هو 10",
    "data-parsley-type":"number",
    "data-parsley-type-message":"يجب ان يحتوى الحقل على ارقام فقط",
    "data-parsley-pattern":"/(05)[0-9]/",
    "data-parsley-pattern-message":"يجب ادخال صيغة هاتف صحيحة"
    
    

});

$('.phone').attr({
    "data-parsley-maxlength" :"25",
    "data-parsley-maxlength-message": "اقصى عدد للارقام مسموح به هو 25",
    "data-parsley-minlength" :"10",
    "data-parsley-minlength-message":"اقل عدد ارقام مسموح به هو 10",
    "data-parsley-type":"number",
    "data-parsley-type-message":"يجب ان يحتوى الحقل على ارقام فقط",
    "data-parsley-pattern":"/(05)[0-9]/",
    "data-parsley-pattern-message":"يجب ادخال صيغة هاتف صحيحة",
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",

});



$('.identity').attr({
    
    "data-parsley-maxlength" :"25",
    "data-parsley-maxlength-message": "اقصى عدد للارقام مسموح به هو 25",
    "data-parsley-minlength" :"7",
    "data-parsley-minlength-message":"اقل عدد ارقام مسموح به هو 7",
    "data-parsley-type":"number",
    "data-parsley-type-message":"يجب ان يكون الحقل رقما",
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",

});

$('.address').attr({
    "data-parsley-maxlength" :"200",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 200 ",
    "data-parsley-minlength" :"3",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف",
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
});

$('.description').attr({
    "data-parsley-maxlength" :"250",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 250 ",
    "data-parsley-minlength" :"3",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف",
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
});

$('.text').attr({
    "data-parsley-maxlength" :"2500",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 2500",
    "data-parsley-minlength" :"3",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف",
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
});

$('.msg_body').attr({
    "data-parsley-trigger": "focusout",
    "data-parsley-required-message":"هذا الحقل إلزامي",
    "data-parsley-maxlength" :"2000",
    "data-parsley-maxlength-message": "تجاوزت الحد الأقصى لعدد الحروف المسموحة وهى 2000 ",
    "data-parsley-minlength" :"3",
    "data-parsley-minlength-message":"اقل عدد حروف مسموح به هو 3 حروف",
    
});

// $('body').delegate('.numbersOnly', 'keyup', function (event) {

//     var limit = $(this).attr('data-limit');
//     var message = $(this).attr('data-message');
//     var field = $(this).attr('name');

//     if (!limit) {
//         limit = 25;
//     }

//     if (this.value.length > limit) {
//         $(this).css({
//             'border': '1px solid red',
//             'font-size': '14px',
//             'color': 'red'
//         });

//         /****Not Work yet****/


//         $('.' + field).css({
//             'font-size': '12px'
//         });


//         $('.' + field).html(message);


//     } else {
//         $(this).removeAttr('style');
//         $('.' + field).html('');

//     }

//     if (this.value.length > limit + 1) {
//         event.preventDefault();
//         return false;
//     }


//     this.value = this.value.replace(/[^0-9\.]/g, '');
// });

