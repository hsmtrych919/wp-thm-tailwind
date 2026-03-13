jQuery(function($) {
  $('#taxonomy-voice_category .children input').change(function() {
    function parentNodes(checked, nodes) {
      var parents = nodes.parent().parent().parent().prev().children('input');
      if (parents.length != 0) {
        parents.prop('checked', checked);
        parentNodes(checked, parents);
      }
    }
    var checked = $(this).is(':checked');
    $(this).parent().parent().siblings().children('label').children('input').each(function() {
      checked = checked || $(this).is(':checked');
    });
    parentNodes(checked, $(this));
  });
});
