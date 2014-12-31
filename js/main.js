$(function(){
  $('.file-sub-btn').click(function(e){
    e.preventDefault();
    var theForm = $(e.target).closest('form');
    if (validateFileSub(theForm, '.csv')) {
      theForm.submit();
    } else {
      return 0;
    }
  });

  function validateFileSub(formEl, extension) {
    var passed;
    formEl.find('input[type=file]').each(function(){
      var theFileObj = $(this).prop('files');
      var ExtensionRegEx = new RegExp(extension);
      if (theFileObj.length < 1) {
        alert('Looks like we\'re missing a file. Can you try again?');
        passed = false;
        return false;
      } else if (!theFileObj[0].name.match(ExtensionRegEx)) {
        alert('Looks like the wrong file-type was submitted. We\'re looking for a ' + extension + ' file');
        passed = false;
        return false;
      } else {
        passed = true;
      }
    });

    return passed;
  }
});