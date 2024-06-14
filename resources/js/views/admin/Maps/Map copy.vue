<template>
    
    <v-container>
        
        <v-row>
            <v-col cols="12" md="8">
                <div v-if="competittors.length>0" id="map" style="width: 100%; height: 600px;"></div>
            </v-col>
            <v-col cols="12" md="4">
                <br>
                <h3>
                    
                </h3>
                <v-slider
                    v-model="sliderValue"
                    step="1"
                    ticks
                    label="Circle Distance(km)"
                    thumb-label="always"
                    min="1"
                    max="10"
                ></v-slider>

                <v-select  
                    label="Side menu"
                    v-model="selectsidebar"
                    :items="branches"
                    item-text="title"
                    return-object
                    @change="move"
                    
                    
                >
                        <template v-slot:item="{ item }">
                            <img v-if="item.icon" :src="currentDomain+'/storage/'+item.icon" id="images">{{ item.title }}
                          
                        </template>
                  
                </v-select>
                       
                <div style="height:250px;overflow-y: auto;">
                    <v-list dense>
                        <v-list-item-group
                            color="primary"
                          
                        >
                            <v-list-item v-for="branch in branchCompetitors" :key="branch.id">
                                <v-list-item-icon>
                                    <img v-if="branch.icon" :src="currentDomain+'/storage/'+branch.icon">
                                </v-list-item-icon>
                                <v-list-item-content @click="move(branch)">
                                    <v-list-item-title>{{ branch.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            
                        </v-list-item-group>
                    </v-list>
                </div>  
      
                <!-- <v-list>
                    <v-list-item-group
                        color="primary"
                       
                    >
                        <v-list-item v-for="branch in branches" :key="branch.id">
                            <v-list-item-icon>
                                <img v-if="branch.icon" :src="currentDomain+'/storage/'+branch.icon">
                            </v-list-item-icon>
                            <v-list-item-content @click="move(branch)">
                                <v-list-item-title>{{ branch.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        
                    </v-list-item-group>
                </v-list> -->
                
                
               
            </v-col>
        </v-row>
       
    </v-container>
  </template>
  <style>
  #images {
    padding: 10px !important;
    height: 40px !important;
    margin: 5px !important;
  }
</style>
  <script>
  import { Loader } from '@googlemaps/js-api-loader'; 
  import axios from "axios";
  const api='AIzaSyCNar5ErL5xZGxRappqcZ1sBbUlfmraG-U'

  export default {
    name: 'GoogleMap',
    data() {
      return {
        currentDomain: window.location.origin,
        map: null,
        marker: null,
        competittorsSample:[],
        competittors:[],
        circle:null,
        sliderValue:3,//meter
        selectsidebar:'',
      };
    },
   
    mounted() {
        this.initialize();
    },
    computed:{
        branches(){
            return this.competittors.filter(x=>x.branch_id!=null&&x.is_competitor==false);
        },
        branchCompetitors(){
            return this.selectsidebar?this.competittors.filter(x=>x.branch_id==this.selectsidebar.branch_id&&x.is_competitor)
            :this.competittors.filter(x=>x.branch_id!=null);
        }
    },
    methods: {
        initialize() {
                return new Promise((resolve, reject) => {
                axios
                .get("/api/mapLocations")
                .then(response => {
                    if(response){
                        this.competittors = response.data.data;
                        setTimeout(() => {
                            this.initMap();
                            this.loading = false;
                            resolve(response);
                        }, 100);
                    }
                  
                })
                .catch(error => {
                    reject(error);
                });
            });
        },
        
        initMap() {
            if(this.competittors.length>0){
            // Load the Google Maps API asynchronously
                const loader = new Loader({
                    apiKey: api,
                    version: "weekly",
                });
                loader.load().then(async () => {
                    const { Map } = await google.maps.importLibrary("maps");
                    const { Marker,PinElement } = await google.maps.importLibrary("marker");
                    this.map = new Map(document.getElementById("map"), {
                        center: { lat: -34.397, lng: 150.644 },
                        zoom: 8,  
                        scaleControl: true,
                    }); 
                    
                    // This marker//
                    const svgMarker = {
                        path: "M-1.547 12l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM0 0q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
                        fillColor: "blue",
                        fillOpacity: 0.6,
                        strokeWeight: 0,
                        rotation: 0,
                        scale: 2,
                        anchor: new google.maps.Point(0, 18),
                    };
                        // Create a marker and add it to the map
                        this.marker = new Marker({
                            position: { lat: -34.397, lng: 150.644 },
                            map: this.map,
                            title: 'Marker Title', // Optional: Set a title for the marker
                            icon: svgMarker,
                            
                        });
                        // Create a marker and add it to the map1
                        this.marker = new Marker({
                            position: { lat: -34.397, lng: 150.644 },
                            map: this.map,
                            title: 'Marker Title', // Optionalhandler.apply: Set a title for the marker
                            icon: svgMarker,
                            
                        });
                        this.competittorsSample.forEach(element => {
                        //   console.log(element);
                        const temp=new Marker({
                            name:element.name,
                            name_branch_manager:element.name_branch_manager,
                            cultural_level:element.cultural_level,
                            price_program:element.price_program,
                            specail_program:element.specail_program,
                            address:element.address,
                            qaunity_staff:element.qaunity_staff,
                            quanity_student:element.quanity_student,
                            social_media:element.social_media,
                            other:element.other,
                            description:element.description,
                            program:element.program,
                            position: element.position,
                            phone:element.phone,
                            map: this.map,
                            title: element.title, // Optional: Set a title for the marker
                            icon: svgMarker
                            // content: pinGlyph.element,
                        });
                        temp.addListener('click' ,  () => {
                            infoWindow.setContent("<h6 style = 'width:200px;min-height:40px;margin:0;color:blue;'>" +
                                 'Name:'+temp.name+'</h6>' +'name manager:'+temp.name_branch_manager + '<br>' + 
                                 'Description:'+temp.description +'<br>'+'Program:'+temp.program+'<br>'+'Phone:'+temp.phone );
                            infoWindow.open(map,temp);
                        });
                    });
                        window.onload = function () {
                            LoadMap();
                        }
                        var infoWindow = new google.maps.InfoWindow();
                        // var data = markers[i];
                        function LoadMap() {
                        const mapOptions = {
                        center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
                        zoom: 10,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
                        for (var i = 0; i < markers.length; i++) {
                        var data = contentString [i];
                        var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: map,
                            title: data.title
                        }); 
                    }
                }
                   
                this.marker.addListener('click' ,  () => {
                    infoWindow.setContent( );
                    infoWindow.open(map,this.marker);
                });   
                    const pinGlyph = new PinElement({
                        glyphColor: "white",
                    });
                    var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
                    this.competittors.forEach(element => {
                        const temp= new Marker({
                            name:element.name,
                            name_branch_manager:element.name_branch_manager,
                            cultural_level:element.cultural_level,
                            price_program:element.price_program,
                            specail_program:element.specail_program,
                            address:element.address,
                            qaunity_staff:element.qaunity_staff,
                            quantity_student:element.quantity_student,
                            social_media:element.social_media,
                            other:element.other,
                            position: element.position,
                            map: this.map,
                            title: element.title, // Optional: Set a title for the marker
                            icon: element.icon?{
                                url:this.currentDomain+'/storage/'+element.icon,
                                anchor: new google.maps.Point(20, 40),
                            }:null,
                            
                            // content: pinGlyph.element,
                        });
                        // console.log(temp);
                        temp.addListener('click' ,  () => {
                            infoWindow.setContent("<h6 style = 'width:200px;min-height:40px;margin:0;color:blue;'>" +
                                temp.name+'</h6>' +'Name manager:'+temp.name_branch_manager + '<br>' + 'Cultural level:'+temp.cultural_level +
                                 '<br>'+'Price Program:'+temp.price_program+'<br>'+'Specail_program:'+temp.specail_program+'<br>' + 'Address:'+
                                  temp.address+'<br>'+'Qaunity staff:'+temp.qaunity_staff+'<br>'+'Quanity student:'+temp.quantity_student+'<br>'+'Social Media:'+temp.social_media);
                            infoWindow.open(map,temp);
                        });
                    });
                });
            }
        },
        
        move(marker){
            //reset circle
            if(this.circle)this.circle.setMap(null);
            // this.map.panTo(marker.position)
            this.map.panTo(marker.position)
            this.map.setZoom(14);

            //add circle
            this.circle = new google.maps.Circle({
                map: this.map,
                center: marker.position,
                radius: this.sliderValue*1000, // Radius in meters
                strokeColor: "#FF0000", // Circle stroke color
                strokeOpacity: 0.7, // Circle stroke opacity (0.0 to 1.0)
                strokeWeight: 2, // Circle stroke weight
                fillColor: "#FF0000", // Circle fill color
                fillOpacity: 0.15, // Circle fill opacity (0.0 to 1.0)
            });

            // Optional: Adjust the map to fit the marker and circle within the viewport
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(marker.position);
            bounds.union(this.circle.getBounds());
            this.map.fitBounds(bounds);

            //count markers
            const circleCenter = this.circle.getCenter();
            const circleRadius = this.circle.getRadius();

            let count = 0;

            this.competittors.forEach((marker) => {
                if(google.maps.geometry){
                    const markerPosition = marker.position;
                    console.log(google.maps)
                    const distance =google.maps.geometry.spherical.computeDistanceBetween(circleCenter, markerPosition);
                    
                    if (distance <= circleRadius) {
                    count++;
                    }
                }  
            });
            // window.initMap = this.initMap();   
            }   
            },   
        };
        </script>
        
        <style>
        #map {
            height: 100%;
        }

</style>
  