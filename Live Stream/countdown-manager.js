var debug = false;


//these items are custom, non-countdown.js functions that should happen when an event status is set or changed.
countdownStates = {
  live : function(){
    $('body').removeClass('countdown-offline');
    $('body').removeClass('countdown-prelive');    
    $('body').addClass('countdown-live');
  },
  offline :function(){
    $('body').removeClass('countdown-prelive');
    $('body').removeClass('countdown-live');    
    $('body').addClass('countdown-offline');
  },
  prelive: function(){
    $('body').removeClass('countdown-offline');
    $('body').removeClass('countdown-live');
    $('body').addClass('countdown-prelive');
  }
}


function initCountdown(){

      
    var idx = 0; //counter for events

    //retreve source list of events. This should contain events that are in progress, or are in the future.
    $.getJSON("/_ajax/countdown-times.php",function(data){
        
        
        if(debug){
            console.log('Input Data:');
            console.log(data);            
        }

        var liveLink = "/live-service";

        //the labels used in the countdown timer
        var lbs = ["YR","MO", "WK", "days","hrs","min","sec"];
            
        //the layouts tha show which data is presented
        var layoutTimer = "{dnn}<span>{dl}</span> {hnn}<span>{hl}</span> {mnn}<span>{ml}</span> {snn}<span>{sl}</span>";
        var layoutDescOnly = "{desc}";

        //the html div that holds the countdown timer
        var chtml = $("#cnt");


        if(typeof data != 'undefined' && data != null && data.length > 0 && typeof data[idx] != 'undefined'){

            //get the first event properties...
            var starttime = new Date(data[idx].date.start);
            var endtime = new Date(data[idx].date.end);
            var curtime = new Date();

            //set up the countdown timer appropriately
            prepEvent(curtime, starttime, endtime);
        }
        else{

            //no events scheduled

            if(debug){
                //first event...
                console.log('No live events scheduled');
            }   

            chtml.countdown({description:"<span class='livelink'><a href='"+liveLink+"'>Offline</a></span>",layout:layoutDescOnly});            
            countdownStates.offline();
        }


        

        //given a start time and an end time, show the live event or kickoff the counter. Also set the next actions for when the event
        //ends or the timer is done counting down.
        function prepEvent(curtime, starttime, endtime){


            if(debug){
                //first event...
                log();
            }            

            //is the event live.....
            if(curtime <= endtime && curtime >= starttime){

                if(debug){
                    console.log("Service is live");
                }

                chtml.countdown({until:endtime, format:'DHMS', onExpiry:setNext, description:"<span class='livelink'><a href='"+liveLink+"'>Service Is Live</a></span>",labels:lbs,labels1:lbs,layout:layoutDescOnly});
                countdownStates.live();
                
            }
            //is the event in the future....
            else if(curtime < starttime){
                
                if(debug){
                    console.log("Service is upcoming.... countdown to this event at " + starttime);
                }

                chtml.countdown({until:starttime, format:'DHMS', onExpiry:showLive, labels:lbs,labels1:lbs,layout:layoutTimer});
                countdownStates.prelive();

            }
            //is the event over...
            else if(curtime > endtime){

                if(debug){
                    console.log("Service is in the past. This should not have come from the input data :(");
                }

            }else{

                if(debug){
                    console.log("Event is not in the past, the future, nor is it live. If you're seeing this...it's gonna be a long day.");
                }

            }

        }



        /*
         * Shows a live event, this is fired when a coundown timer is finished
         */
        function showLive(){
            
            
            if(debug){
                console.log("Making counter go live:");
                log();
            }

            chtml.countdown({until:endtime, format:'DHMS',onExpiry:setNext, description:"<span class='livelink'><a href='"+liveLink+"'>Service Is Live</a></span>",labels:lbs,labels1:lbs,layout:layoutDescOnly});
            countdownStates.live();

        }


        
        /*
         * Prepares the next event data...used for when a live service is over and we want to restart the countdown timer.
         */
        function setNext(){
            
            //point to the next event
            idx++; 

            if(data.length > 0 && typeof data[idx] != 'undefined'){         

                //reset my variables
                starttime = new Date(data[idx].date.start);
                endtime = new Date(data[idx].date.end);
                curtime = new Date();


                if(debug){
                    console.log("Prepping Next Event:");
                    log();
                }

                prepEvent(curtime, starttime, endtime);

            }
            else{

                //no events scheduled

                if(debug){
                    //first event...
                    console.log('No additional live events scheduled');
                }   

                chtml.countdown({description:"<span class='livelink'><a href='"+liveLink+"'>Offline</a></span>",layout:layoutDescOnly});            
                countdownStates.offline();

            }            

        }
    

      
        /* Log an event canditate with the pertinent information */
        function log(){
            console.log("-----------------------------------------------------------");
            console.log("Event Candidate: ")
            console.log("CURTIME:   "+ curtime);
            console.log("STARTTIME: "+ starttime);
            console.log("ENDTIME:   "+ endtime);
            console.log("-----------------------------------------------------------");
        }            

    });

}