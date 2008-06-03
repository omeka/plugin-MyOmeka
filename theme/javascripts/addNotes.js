Event.observe(window, 'load', function(){
    
    // Hide the form
    $$("#myomeka-edit-note").invoke("hide");
    
    // Set up edit link
    $$("#myomeka-edit-link").invoke("observe", "click", function(e){
        $$("#myomeka-display-note").invoke("hide");
        $$("#myomeka-edit-note").invoke("show");
        Event.stop(e);
    });
    
    // Set up cancel link
    $$("#myomeka-cancel-edit").invoke("observe", "click", function(e){
        $$("#myomeka-display-note").invoke("show");
        $$("#myomeka-edit-note").invoke("hide");
        Event.stop(e);
    });
});