$(document).ready(function(){
    var newQuestionLabels = ["Question", "Answer1", "Answer2", "Answer3", "Answer4", 
                             "Answer5", "Answer6"];
    
    $("#addQuestion").click(function() {
        var selectedQuestionType = $("#questionType option:selected").val();
        
        if(selectedQuestionType == "Select Question Type"){
            //error show when the user didn't choose type
            $("#select_error").text("Selecte Question Type");
        }
        else{
            //this will be executed when the user choose type 
            addQuestion();
            $("#select_error").text("");
        }
    });

    function addQuestion(){
        //create the containers and all the needed things
        //to create a question card
        var newContainer = document.createElement("div");
        newContainer.setAttribute("class", "container");
        
        var newCard = document.createElement("div");
        newCard.setAttribute("class", "card shadow border-left-warning py-2");
        
        var newCardBody = document.createElement("div");
        newCardBody.setAttribute("class", "card-body");
        //container is ready
        
        //take the selected question type
        var selectedQuestionType = $("#questionType option:selected").val();

        //create all the needed things 
        //if the question is MCQ
        if(selectedQuestionType == "Multiple Choice Question"){
            //7 because we want only 7 answers
            for(i = 0; i < 7; i++){
                //create the label
                var newLabel = document.createElement("label");
                newLabel.innerHTML = newQuestionLabels[i];
                //append the label to the cardbody
                newCardBody.appendChild(newLabel);
                
                //create the input
                var newInput = document.createElement("input");
                newInput.setAttribute("class", "form-control");
                newInput.setAttribute("type", "text");
                //append the input to the cardbody
                newCardBody.appendChild(newInput);

                //add the type of the question at the container
                //to know when update the id how to navigate to the child
                newContainer.setAttribute("type", "MCQ");
            }  
        }

        //create all the needed things
        //if the question is Written
        else if(selectedQuestionType == "Written Question"){
            //create the label
            var questionLabel = document.createElement("label");
            questionLabel.innerHTML = "Question";
            //append the label to the cardbody
            newCardBody.appendChild(questionLabel);

            //create the input 
            var newInput = document.createElement("input");
            newInput.setAttribute("class", "form-control");
            newInput.setAttribute("type", "text");
            //append the label to the cardbody
            newCardBody.appendChild(newInput); 

            //add the type of the question at the container
            //to know when update the id how to navigate to the child
            newContainer.setAttribute("type", "Written");
        }
        
        //create the container of the trash icon 
        //and create the trash icon
        var divTrashIcon = document.createElement("div");
        divTrashIcon.setAttribute("style", "font-size: 50px; text-align: right;");
        
        var trashIcon = document.createElement("i");
        trashIcon.setAttribute("class", "icon ion-trash-b");
        trashIcon.setAttribute("style", "color:rgb(115,62,147)");
        
        divTrashIcon.appendChild(trashIcon);
        
        //append the container of the trash icon
        //to the cardbody
        newCardBody.appendChild(divTrashIcon);
        
        //append the cardbody to the card and
        //append the card to the container
        newCard.appendChild(newCardBody);
        newContainer.appendChild(newCard);
        
        //append the created question to the form
        $("#questionsForm").append(newContainer);
        updateQuestionsIDs();
    }
});


function deleteQuestion(questionID){
    var containers = $("#questionsForm").children();
    for(i = 0; i<containers.length; i++){
        var id = containers[i].getAttribute("id");
        if(questionID == id){
            containers[i].remove();
            break;
        }
    }
    updateQuestionsIDs();
}


function updateQuestionsIDs(){
    var children = $("#questionsForm").children();
    var MCQNumber = 0;
    var WrittenNumber = 0;

    //we check for 3 because the form 
    //always contain a div and two labels that have 
    //the survey information and the submit button
    //and the labels have the numbers of questions 
    //also we start id 3 to the index to jump
    //the three elements at the beginning
    if(children.length != 1){

        for(i = 0; i < children.length; i++){
            //set the container id
            $(children[i+3]).attr("id", i);

            var type = $(children[i+3]).attr("type");
            var inputs = $(children[i+3]).find("input");

            if(type == "MCQ"){
                //count the number of all the MCQ questions
                MCQNumber++;

                //update the ids for the inputs in any MCQ question 
                //we use the MCQNumber insted of i because we separate
                //the MCQ and the Written questions
                for(j = 0; j < 7; j++){
                    $(inputs[j]).attr("name", "MCQ"+j+"id"+MCQNumber);
                }
            }
            else if(type == "Written"){
                //count the number of all the Written questions
                WrittenNumber++;

                //update the ids for the inputs in any Written question
                //we use the WrittenNumber insted of i because we separate
                //the MCQ and the Written questions
                $(inputs[0]).attr("name", "Writtenid"+WrittenNumber);
            }

            //update the id for the icon for the onclick attribute
            var icon = $(children[i+3]).find("i");
            $(icon).attr("onclick", "deleteQuestion("+i+")")
        }



        //these are two labels at the end fo the form which is have
        //the number of the mcq questions and the written questions
        $(children[1]).attr("value", MCQNumber);
        $(children[2]).attr("value", WrittenNumber);
    } 
}