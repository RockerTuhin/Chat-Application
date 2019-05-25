<template>
	<v-layout row>
	  <v-flex class="online-users" xs3>
	    <v-list class="mb-5">
	      <v-list-tile v-for="friend in friends" :key="friend.id" @click="selectFriendId=friend.id">
	        <v-list-tile-action>
	          <v-icon :color="(onlineUsers.find(onlineUser=>onlineUser.id === friend.id))?'green':'red'">account_circle</v-icon>
	        </v-list-tile-action>
	        <v-list-tile-content :class="{'green--text': friend.id == selectFriendId}">
	          <v-list-tile-title>{{ friend.name }}</v-list-tile-title>
	        </v-list-tile-content>
	        <!-- <v-list-tile-avatar>
	          <img :src="item.avatar">
	        </v-list-tile-avatar> -->
	      </v-list-tile>
	    </v-list>
	  </v-flex>
	  <v-flex id="privateMessageBox" class="messages mb-5" xs9>
	    <v-list>
        <v-list class="p-2" v-for="(item,index) in allMessages" :key="index">
          <v-layout :align-end="(user.id !== item.user.id)" column :class="{'hlw': user.id !== item.user.id,'hey': user.id === item.user.id}" column >
          	<div class="message-wrapper">
	            <v-flex>
	              <span class="small font-italic" :class="{'green--text': user.id !== item.user.id,'blue--text': user.id === item.user.id}">{{ item.user.name }}</span>
	            </v-flex>
	            <div v-if="item.message" class="text-message-container" style="text-align: justify;">
	              	  {{ item.message }}
	            </div>
	            <div class="image-container">
	            	<img class="tuhin" v-if="item.image" :src="'/PersonalChat/storage/app/'+item.image">
	            </div>
	            <v-flex class="caption font-italic" :class="{'green--text': user.id !== item.user.id,'blue--text': user.id === item.user.id}">
	              {{ item.created_at }}
	            </v-flex>
		    </div>
  		   </v-layout>
        </v-list>
        <span v-if="typingFriend.name">{{ typingFriend.name }} is typing</span>
        </v-list>
        </v-flex>
        <div class="floating-div ml-4">
        	<picker v-if="emoStatus" class="floating-div" set="emojione" title="Pick your emoji..." @select="onInput" />
        </div>
	    <v-footer height="auto" fixed color="grey">
	    	<v-layout row > 
	    		<v-flex xs1 class="ml-12 text-right" offset-xs1>
	    			<v-btn fab dark small color="pink" @click="toggleEmo">
	    				<v-icon>insert_emoticon</v-icon>
	    			</v-btn>
	    		</v-flex><!-- @input-file="$refs.upload.active = true" -->
	    		<v-flex xs1 class="text-center">
	    			<file-upload :post-action="'private-messages/'+selectFriendId" @input-file="$refs.upload.active = true" ref="upload" :headers="{'X-CSRF-TOKEN': token}">
	    				<v-icon class="mt-3">attach_file</v-icon>
	    			</file-upload>
	    		</v-flex>
		      <v-flex xs6 justify-center align-center>
		      	<v-text-field rows=2 label="Enter Message" single-line v-model="message" @keyup.enter="sendMessagePrivate" @keydown="onTyping"></v-text-field>   
		      </v-flex>
		      <v-flex xs4>
		      	<v-btn dark class="mt-3 ml-2 white--text" @click="sendMessagePrivate">send</v-btn>
		      </v-flex>
	     </v-layout>       
	    </v-footer> 
	  <!-- </v-flex> -->
	</v-layout>
</template>

<script>
	//FOR USING emoji-mart-vue-fast COMPONENT 
	import { Picker } from 'emoji-mart-vue-fast'
	import 'emoji-mart-vue-fast/css/emoji-mart.css'
	//END FOR USING emoji-mart-vue-fast COMPONENT 

	export default {
		props:['user'],
		components:{
			Picker
		},
		data() {
			return {
				message:null,
				selectFriendId:null,
				allMessages:[],
				users:[],
				typingFriend:{},
				typingClock:null,
				onlineUsers:[],
				token:document.head.querySelector('meta[name="csrf-token"]').content,
				emoStatus:false,
			}
		},
		computed:{
			friends(){
				return this.users.filter((user)=>{
					return user.id !== this.user.id;
				})
			}
		},
		watch:{
			selectFriendId(val){
				this.fetchMessagesPrivate();
			}
		},
		methods:{
			onTyping(){
				setTimeout(this.scrollToEnd,1000);

				Echo.private('privatechat'+this.selectFriendId)
				.whisper('typing', {
					user: this.user
				});
			},
			scrollToEnd(){
				//document.getElementById("privateMessageBox").scrollTo(0,9999999);
				window.scrollTo(0,99999);
			},
			sendMessagePrivate(){
				//check if there message
				if(!this.message){
					return alert('Please enter your message');
				}
				if(!this.selectFriendId){
					return alert('Please select friend');
				}
				//send post request
				axios.post('private-messages/'+this.selectFriendId,{
					message: this.message
				})
				.then( response => {
					console.log(response.data);
					this.message = null;
					// this.allMessages = response.data.message; 

					this.fetchMessagesPrivate();
					setTimeout(this.scrollToEnd,1000);
				})
				.catch( error => {
					console.log(error);
				});
			},
			fetchMessagesPrivate(){
				if(!this.selectFriendId){
					return alert('Please select friend');
				}
				axios.get('private-messages/'+this.selectFriendId)
				.then( response => {
				    this.allMessages = response.data;
				    console.log(response.data);
				})
				.catch( error => {
				    // handle error
				    console.log(error);
				})
			},
			fetchUsers(){
				axios.get('users')
				.then( response => {
				    this.users = response.data;
				    this.selectFriendId = this.friends[0].id;
				    //this.fetchMessagesPrivate();
				    console.log(response.data);
				})
				.catch( error => {
				    // handle error
				    console.log(error);
				})
			},
			toggleEmo(){
				this.fetchMessagesPrivate();
				this.emoStatus = !this.emoStatus;
			},
			onInput(e){
				console.log(e);
				if(!e){
					return false;
				}
				if(!this.message){
					this.message = e.native;
				}
				else
				{
					this.message = this.message + e.native;
				}
			}
			
		},
		mounted(){
			this.fetchUsers();

			Echo.join(`onlineStatus`)
			.here((users) => {
				this.onlineUsers = users;
		        console.log('online users',users);
		    })
			.joining((user) => {
				this.onlineUsers.push(user);
				console.log('joining',user.name);
			})
			.leaving((user) => {
				this.onlineUsers.splice(this.onlineUsers.indexOf(user),1);
				console.log('leaving',user.name);
			});


			Echo.private('privatechat'+this.user.id)
			.listen('PrivateMessageSent', (e) => {
				this.selectFriendId = e.message.user_id;
				this.allMessages.push(e.message);
				this.fetchMessagesPrivate();
				setTimeout(this.scrollToEnd,1000);
				console.log(e.message);
			})
			.listenForWhisper('typing', (e) => {
				if(e.user.id == this.selectFriendId){

					this.typingFriend = e.user;

					if(this.typingClock) clearTimeout();
				 	this.typingClock=setTimeout(()=>{
				 		this.typingFriend = {};
				 	},3000);
				}
			});
			//this.fetchMessagesPrivate();
		},
		created(){
			//this.fetchMessagesPrivate();
		}

	}
</script>

<style scoped>
	.tuhin {
		max-width:300px;
		max-height:200px;
	}
	.floating-div{
		position: fixed;
	}
	.hlw{
		margin-left:150px;
	}
	.hey{
		margin-right:150px;
	}
</style>

