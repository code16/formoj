<template>
    <div id="app">
        <div class="container">
            <h1>Formoj Example</h1>
            <hr>
            <fj-form
                :title="form.title"
                :description="form.description"
                :sections="form.sections"
                @submit="handleSubmit"
            />
        </div>
    </div>
</template>

<script>
  export default {
      name: 'app',
      computed: {
          form() {
              return {
                  "title": "Questionnaire",
                  "description": "Test",

                  "sections": [
                      {
                          "id": 1,
                          "title": "Ma section",
                          "description": "Premières questions",

                          "fields": [
                              {
                                  "id": 1,
                                  "type": "text",
                                  "label": "Nom",
                                  "required": true,
                                  "maxlength": 10
                              }
                          ]
                      },
                      {
                          "id": 2,
                          "title": "Ma section 2",
                          "description": "Deuxième questions",

                          "fields": [
                              {
                                  "id": 2,
                                  "type": "text",
                                  "label": "Nom",
                                  "required": true,
                                  "maxlength": 10
                              }
                          ]
                      }
                  ]
              }
          }
     },

      methods: {
          handleSubmit() {
              alert('Formulaire envoyé!');
          }
      }
  }
</script>

<style lang="scss">
    @import "./scss/app";
</style>