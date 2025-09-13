$(function() {
       
        $("#menuform").validate({
          rules:{
            short_order:{
              required:true,
              number:true
          },
          title:{
              required:true
          },
          slug:{
              required:true
          },
          
          position:{
              required:true,
          },
          route:{
            url:true
        },
        status:{
          required:true
      }
      
  },
  messages:{
    position:'Please select position',
    short_order:'Please enter order data',
    title:'Please enter title',
    slug: 'Plese enter slug',
    status: 'Plese choose status'
}
});
    });