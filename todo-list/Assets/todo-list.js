const template = `
<div> 
  <div class="taskList">
      <div v-if='showList'>
          <div v-for='item in list'>
            <div v-if='item.status'>
              <label title="Completed" class="container disabled">
                {{item.description}} - {{dateString(item.date)}}
                <input type="checkbox" checked="checked" disabled="disabled">
                <span class="checkmark"></span>
              </label>
            </div>
            <div v-else>
              <label title="Incomplete" class="container disabled">
                {{item.description}} - {{item.date}}
                <input type="checkbox" disabled="disabled">
                <span class="checkmark"></span>
              </label>
            </div>
          </div>
      </div>
      <p v-else>
          Nothing added yet!
      </p>
  </div>
  <div class="formContainer">
    <label class="taskLabel">Add a task</label><br>
    <label for='description'>
    Description
    </label><br>
    <input type='text' id='description' name='description' ref='description'
        v-model="description" class="matchingInput"><br>
    <label for='date'>
        Date
    </label><br>
    <input type='date' id='date' name='date'
        v-model="date" class="matchingInput dateInput"><br>
    <label class="container">Complete
      <input type="checkbox" v-model="status">
      <span class="checkmark"></span>
    </label>
    <button v-on:click='saveButtonClicked'>Submit</button>
  </div>
</div>`;

const path = 'wp/v2/todo';

( function() {
  var vm = new Vue({
    el: document.querySelector('#mount'),

    template: template,

    // Load todos after mounting app
    mounted: function(){
      wp.apiRequest( { path: path } )
        .then( todos => { 
          for (let i = todos.length - 1; i >= 0; i--) {
            console.log(todos[i].tododescription - todos[i].tododate - todos[i].todostatus);
            this.pushTodo(todos[i].tododescription, todos[i].tododate, todos[i].todostatus);
            } 
        }
      );
    },

    data: {
      list: [],
      description: "",
      date: "",
      status: "",
      posts: ""
    },

    methods: {

      // Push todos to list for display
      pushTodo(description, date, status) {
        this.list.push({description: description, date: date, status: status});
      },

      // Submit form and save todo
      saveButtonClicked() {
        if (this.isValid())
        {
          this.pushTodo(this.description, this.date, this.status);
          wp.apiRequest( {
            path: path,
            method: 'POST',
            data: {
              title: 'Todo Post',
              status: "publish",
              tododescription: this.description,
              tododate: this.date,
              todostatus: this.status,
            }
          } ).then( todo => console.log( todo ) );
          this.description = "";
          this.date = "";
          this.status = "";
          this.$refs.description.focus();
        }
        else
        {
          alert("Please add a valid description and date.");  
        }
      },

      // Whether or not the form is valid to be saved
      isValid() {
        return (this.description != "" ) && (this.date != "" );
      },

       // Convert date string for display
      dateString(date) {
        return new Date(date).toDateString();
      }

    },
    computed: {
      
      // Whether or not we have any entries to display
      showList() {
        return (this.list.length > 0);
      },
    }
  });
})();
