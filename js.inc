var ajax_comment_loading = false;
function ajax_comments_loading(on) { if(on) {
  ajax_comment_loading = true;
  var f = $('commentform');
  f.submit.disabled = true; // disable submit
  new Insertion.Before(f, '<div id="ajax_comments_loading" style="display:none;">Submitting Comment...</div>'); // create loading

  var l = $('ajax_comments_loading');
  new Effect.Appear(l, { beforeStart: function() { with(l.style) {
    display = 'block';
    margin = '0 auto';
    width = '100px';
    font = 'normal 12px Arial';
    background = 'url(<?php echo get_settings('siteurl').PLUGIN_AJAXCOMMENTS_PATH; ?>loading.gif) no-repeat 0 50%';
    padding = '0 0 0 23px';
  }}});
} else {
  new Effect.Fade('ajax_comments_loading', { afterFinish: function() { // hide loading
    Element.remove('ajax_comments_loading'); // dystroy loading
  }});
  $('commentform').submit.disabled = false; // enable submit
  ajax_comment_loading = false;
}}

function rotate_auth_image() {
  var img = $('auth-image'), input = $('code');
  if(img) img.src += '?'+Math.random(); // Change AuthImage
  if(input) input.value = ''; // Reset Code
}

function find_commentlist() {
  var e = $('commentlist');
  if(e == null) {
    var e = document.getElementsByTagName('ol');
    for(var i=0; i<e.length; i++)
      if(e[i].className=='commentlist')
        return e[i];
  } else return e;

  /* commentslist doesn't exist (no comments yet)
  so create it above the commentform and return it */
  var f = $('commentform');
  new Insertion.Before(f, '<ol id="commentlist"></ol>'); // create commentform
  return $('commentlist');
}


function ajax_comments_submit() {
  if(ajax_comment_loading) return false;

  ajax_comments_loading(true);
  var f = $('commentform'), ol = find_commentlist();
  new Ajax.Request('<?php echo get_settings('siteurl').PLUGIN_AJAXCOMMENTS_PATH.PLUGIN_AJAXCOMMENTS_FILE; ?>?submit', {
    method: 'post',
    asynchronous: true,
    postBody: Form.serialize(f),
    onLoading: function(request) {
      request['timeout_ID'] = window.setTimeout(function() {
        switch (request.readyState) {
        case 1: case 2: case 3:
          request.abort();
          alert('Comment Error: Timeout\nThe server is taking a long time to respond. Try again in a few minutes.');
          break;
        }
      }, 25000);
    },
    onFailure: function(request) {
      alert((request.status!=406? 'Comment Error '+request.status+' : '+request.statusText+'\n' : '')+request.responseText);
    },
    onComplete: function(request) { ajax_comments_loading(false);
      window.clearTimeout(request['timeout_ID']);
//      rotate_auth_image(); // AuthImage
      if(request.status!=200) return;

      f.comment.value=''; // Reset comment

      new Insertion.Bottom(ol, request.responseText);
      var li = ol.lastChild, className = li.className, style = li.style;
      new Effect.BlindDown(li, {
        afterFinish: function() { li.className = className; li.style = style; }
      });
    }
  });
  return false;
}
