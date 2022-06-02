from flask import Flask, jsonify
from flask import request
from gensim.models import KeyedVectors
import time
import math
import sys
import json

#TODO: separate Heap class
class Heap:
    def __init__(self,min=False):
        self.heap = []
        self.is_min = min

    def insert(self,e):
        self.heap.append(e)
        i = len(self.heap) - 1
        while(True):
            j = math.floor((i-1)/2)
            if(i <= 0 or j < 0):
                break
            if(self.heap[i][1] > self.heap[j][1]):
                temp = self.heap[i]
                self.heap[i] = self.heap[j]
                self.heap[j] = temp
                i = j
            else:
                break

    def remove(self):
        ret = self.heap[0]
        found = -1
        for z in range(len(self.heap)-1,-1,-1):
            if(self.heap[z] != None):
                self.heap[0] = self.heap[z]
                found = z
                break
        if(found == -1): return None

        self.heap[found] = None
        i = 0
        while(True):
            if(i > len(self.heap)-1):
                break
            if(self.heap[i] == None):
                break

            j = 2*i + 1
            k = 2*i + 2

            if(k < len(self.heap) and j < len(self.heap)):
                if(self.heap[k] == None):
                    if(self.heap[j] == None):
                        break
                    else:
                        if(self.heap[i][1] < self.heap[j][1]):
                            temp = self.heap[i]
                            self.heap[i] = self.heap[j]
                            self.heap[j] = temp
                            i = j
                            continue
                        else:
                            break
                elif(self.heap[j] == None):
                    if(self.heap[i][1] < self.heap[k][1]):
                        temp = self.heap[i]
                        self.heap[i] = self.heap[k]
                        self.heap[k] = temp
                        i = k
                        continue
                    else:
                        break
                elif(self.heap[j][1] > self.heap[k][1]):
                    if(self.heap[i][1] < self.heap[j][1]):
                        temp = self.heap[i]
                        self.heap[i] = self.heap[j]
                        self.heap[j] = temp
                        i = j
                        continue
                    else:
                        break
                else:
                    if(self.heap[i][1] < self.heap[k][1]):
                        temp = self.heap[i]
                        self.heap[i] = self.heap[k]
                        self.heap[k] = temp
                        i = k
                        continue
                    else:
                        break
            elif(k > len(self.heap)-1 and j < len(self.heap)):
                if(self.heap[j] == None):
                    break
                else:
                    if(self.heap[i][1] < self.heap[j][1]):
                        temp = self.heap[i]
                        self.heap[i] = self.heap[j]
                        self.heap[j] = temp
                        i = j
                        continue
                    else:
                        break
            elif(k < len(self.heap) and j > len(self.heap)-1):
                if(self.heap[k] == None):
                    break
                else:
                    if(self.heap[i][1] < self.heap[k][1]):
                        temp = self.heap[i]
                        self.heap[i] = self.heap[k]
                        self.heap[k] = temp
                        i = k
                        continue
                    else:
                        break
            else:
                break

        return ret

print("loading model")
sys.stdout.flush()

model = KeyedVectors.load_word2vec_format('./data/lexvec.vectors',binary=False)
words = model.index_to_key
print('model loaded')
sys.stdout.flush()

genres = ["acoustic","afrobeat","alt-rock","alternative","ambient","anime","black-metal","bluegrass",
        "blues","bossanova","brazil","breakbeat","british","cantopop","chicago-house","children",
        "chill","classical","club","comedy","country","dance","dancehall","death-metal","deep-house",
        "detroit-techno","disco","disney","drum-and-bass","dub","dubstep","edm","electro","electronic",
        "emo","folk","forro","french","funk","garage","german","gospel","goth","grindcore","groove","grunge",
        "guitar","happy","hard-rock","hardcore","hardstyle","heavy-metal","hip-hop","holidays","honky-tonk",
        "house","idm","indian","indie","indie-pop","industrial","iranian","j-dance","j-idol","j-pop",
        "j-rock","jazz","k-pop","kids","latin","latino","malay","mandopop","metal","metal-misc","metalcore",
        "minimal-techno","movies","mpb","new-age","new-release","opera","pagode","party","philippines-opm",
        "piano","pop","pop-film","post-dubstep","power-pop","progressive-house","psych-rock","punk",
        "punk-rock","r-n-b","rainy-day","reggae","reggaeton","road-trip","rock","rock-n-roll","rockabilly",
        "romance","sad","salsa","samba","sertanejo","show-tunes","singer-songwriter","ska","sleep",
        "songwriter","soul","soundtracks","spanish","study","summer","swedish","synth-pop","tango","techno",
        "trance","trip-hop","turkish","work-out","world-music"]

genre_split = [['acoustic'], ['afrobeat'], ['alternative', 'rock'], ['alternative'], ['ambient'], ['anime'],
                ['black', 'metal'], ['bluegrass'], ['blues'], ['bossanova'], ['brazil'], ['breakbeat'],
                ['british'], ['cantopop'], ['chicago', 'house'], ['children'], ['chill'], ['classical'],
                ['club'], ['comedy'], ['country'], ['dance'], ['dancehall'], ['death', 'metal'],
                ['deep', 'house'], ['detroit', 'techno'], ['disco'], ['disney'], ['drum', 'bass', 'fast','quick'],
                ['dub', 'jamaica'], ['dubstep'], ['electronic','dance','music'], ['electro'], ['electronic'], ['emo','emotional'], ['folk'], ['forro'],
                ['french'], ['funk'], ['garage'], ['german'], ['gospel','christian'], ['goth'], ['grindcore','grind'], ['groove','groovy'],
                ['grunge'], ['guitar'], ['happy'], ['hard', 'rock'], ['hardcore'], ['hardstyle'],
                ['heavy', 'metal'], ['hip', 'hop'], ['holidays'], ['honky', 'tonk'], ['house'], ['intelligent','dance','music'],
                ['indian'], ['indie'], ['indie', 'pop'], ['industrial'], ['iranian'], ['japanese', 'dance'],
                ['japanese', 'idol'], ['japanese', 'pop'], ['japanese', 'rock'], ['jazz'], ['korean', 'pop'], ['kids'], ['latin'],
                ['latino'], ['malay'], ['mandarin', 'pop'], ['metal'], ['metal', 'miscellaneous'], ['metalcore'],
                ['minimal', 'techno'], ['movies'], ['brazil','pop'], ['new', 'age'], ['new', 'release'], ['opera'],
                ['folk','country','brazil'], ['party'], ['philippines', 'original','filipino'], ['piano'], ['pop'],
                ['pop', 'film'], ['post', 'dubstep'], ['power', 'pop'], ['progressive', 'house'],
                ['psych', 'rock'], ['punk'], ['punk', 'rock'], ['rhythm', 'blues'], ['rainy', 'day'],
                ['reggae'], ['reggaeton'], ['road', 'trip'], ['rock'], ['rock', 'roll'],
                ['rockabilly'], ['romance'], ['sad'], ['salsa'], ['samba'], ['country','brazil'],
                ['show', 'tunes','broadway'], ['singer', 'songwriter'], ['ska','rock','jamaica'], ['sleep'], ['songwriter'], ['soul'],
                ['soundtracks'], ['spanish'], ['study'], ['summer'], ['swedish'], ['synth', 'pop'],
                ['tango'], ['techno'], ['trance'], ['trip', 'hop'], ['turkish'], ['work', 'out'],
                ['world', 'music']]

app = Flask(__name__)

@app.route('/')
def home():
    return "<h1>Expression Web App Api</h1>"

@app.route('/api/spotify-genre-recommendation',methods=['POST'])
def genre_recommender():
    if not request.json or not 'hashtags' in request.json:
        abort(400)

    hashtags = request.json['hashtags'].split(',')
    genre_tag_similarities = dict()
    gts_count = dict()

    for tag in hashtags:
        if(tag not in words): continue
        top_genre_heap = Heap()
        for i in range(len(genres)):
            dist = 0
            for j in range(len(genre_split[i])):
                dist += model.similarity(genre_split[i][j],tag)
            dist /= len(genre_split[i])
            top_genre_heap.insert([i,dist])

        i = 0
        while(i < 5):
            a = top_genre_heap.remove()
            if(genres[a[0]] not in ["kids","children"]):
                if(genres[a[0]] not in genre_tag_similarities):
                    genre_tag_similarities[genres[a[0]]] = float(a[1])
                    gts_count[genres[a[0]]] = 1
                else:
                    genre_tag_similarities[genres[a[0]]] += float(a[1])
                    gts_count[genres[a[0]]] += 1

                i += 1

    top_genre_heap = Heap()
    for key,value in genre_tag_similarities.items():
        top_genre_heap.insert([key,float(value)/float(gts_count[key])])

    gd = dict()
    rec = ""
    for b in range(5):
        if(top_genre_heap.heap[0] == None):
            break
        genre_dist = top_genre_heap.remove()
        rec += genre_dist[0]
        gd[genre_dist[0]] = genre_dist[1]
        if(b < 5 and top_genre_heap.heap[0] != None):
            rec += ","

    recommendation = {
        'genres': rec,
        'genre_dist':gd
    }
    return jsonify({'recommendation':recommendation}) , 200
    #return "<h1>Spotify Genre Recommender</h1><br><p></p>"

if __name__ == "__main__":
    app.run()