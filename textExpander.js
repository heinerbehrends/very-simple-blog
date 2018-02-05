$(function() {
  function replaceText($domInput, $rulesObject) {
    $domInput.keyup(function() {
      var userInput = $domInput.val();
      for (var rule in $rulesObject) {
        if(userInput.indexOf(rule) !== -1) {
          var replaceString = userInput.replace(rule, $rulesObject[rule]);
          $domInput.val(replaceString);
        }
      }
    })
  }
  var textExpansionRules = {
    'cg': 'Code Gorilla',
    'gn': 'Groningen'
  }
  var textArea = $("#text-area");

  textArea.keyup(replaceText(textArea, textExpansionRules));
})
