<img src="https://user-images.githubusercontent.com/23386030/171668679-3e982fbd-1116-4403-bdce-6a867f97b87c.png" alt="expression-logo" width="400">

### _"a social media platform to experience and share art in a personal way"_

1. The base function is a social media application, where users can post art, view, and commment on other user's posts.
2. There is also a live chat section where users can chat with other users.
3. Admins have their own section for database management and user analytics.
4. \[Contextual Song Recommendation\] 
    - A REST API was created (hosted in a separate AWS EC2 instance). The client sends a list of hashtags from a post to this server to receive a list of related music genres (using a naive cosine similarity algorithm for proof of concept).

## 06/02/22 (Danzel Serrano) - Potential Revival of This Website for Academic Research
The revival of this project is due to current interest in whether or not we can make this website more personable and immersive with the help of artificial intelligence.
- A small poll on instagram, albeit having miniscule number of participants, allows us to see that people correlate aesthetics in a subjective manner.
- The question was to look at a picture of a mountain and try to describe that mountain with a song.
- One gave a rap song, one said a ballad, another mentioned "Pictures of Mountains" by Cody Fry (who is an "indiecoustica" artist according to spotify's api)
    - We see people's judgment on aesthetic is subjective to one's current context and experience. 
    - "Pictures of Mountains" was probably chosen because this person knew the title, the "vibe" of the song, and quite possibly the album cover art which is similar in color as the picture presented to participants.
    
**Can we leverage artificial intelligence (learning algorithms, classical online algorithms, etc.) to create a user experience that is more personal based on the user's current state?**
- Can we also do this in a way that is accessible? (Sound design of the website to be a personal experience, not exclusive of those that are visually impaired, etc..)
