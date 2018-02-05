$(function() {
  var textExpansionRules = {
    'cg': 'Code Gorilla',
    'gn': 'Groningen'
  }
  var textArea = $("#text-area");
  textArea.keyup(function() {
    var userInput = textArea.val();
    for (var rule in textExpansionRules) {
      if(userInput.indexOf(rule) !== -1) {
        var replaceString = userInput.replace(rule, textExpansionRules[rule]);
        textArea.val(replaceString);
      }

    }
  })
})
