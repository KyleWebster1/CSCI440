import sqlite3

conn = sqlite3.connect('music_library.db')
cursor = conn.cursor()


def select(values, table, condition):
    for row in cursor.execute("SELECT %s FROM %s WHERE %s LIMIT 5" % (values, table, condition)):
        print(row)


def insertsong(ID, Name, Description, Length, Genre):
    sql = "INSERT INTO SONG(Song_ID, Name, Description, Length, Genre) VALUES('%s','%s','%s','%s','%s')" % (
    ID, Name, Description, Length, Genre)
    cursor.execute(sql)


def insertHolds(Playlist_ID, SONG_ID):
    sql = "INSERT INTO HOLDS(Playlist_ID, Song_ID) VALUES ('%s','%s')" % (Playlist_ID, SONG_ID)
    cursor.execute(sql)


playlistSongs = [['12345', '666'],
                 ['12345', '667'],
                 ['12345', '668'],
                 ['12345', '669'],
                 ['12345', '670']]

for p in playlistSongs:
    insertHolds(p[0], p[1])

songval = [
    ['666', 'Jazzy Jazz', 'Example Music', '180', 'Jazz'],
    ['667', 'Chicken Nugget Piano', 'Tempo Music', '172', 'Jazz'],
    ['668', 'Little Hat Gucci', 'Temp Music', '184', 'Jazz'],
    ['669', 'Top Hat Swagger', 'New Example Music', '120', 'Jazz'],
    ['670', 'Smooth', 'Example Music', '131', 'Jazz']]

for v in songval:
    insertsong(v[0], v[1], v[2], v[3], v[4])

select("Name", "SONG", "Genre = 'Jazz'")
print("--------------------------")
select("SONG.name", "SONG, OWNS", "User_ID = '1' AND OWNS.Song_ID = SONG.Song_ID")
print("--------------------------")
select("PLAYLIST.Name, SONG.Name", "PLAYLIST, SONG, HOLDS",
       "PLAYLIST.User_ID = '2' AND PLAYLIST.Playlist_ID = HOLDS.Playlist_ID AND SONG.Song_ID = HOLDS.Song_ID ORDER BY PLAYLIST.Name")
print("--------------------------")
select("Name, Song_ID", "RECORD_COMPANY, DISTRIBUTES, DISTRIBUTOR",
       "Song_ID IN (SELECT Song_ID FROM SONG WHERE Name = 'Temp') AND DISTRIBUTES.Distributor_ID = DISTRIBUTOR.Distributor_ID AND DISTRIBUTOR.Company_ID = RECORD_COMPANY.Company_ID")
print("--------------------------")
select("PLAYLIST.Name, USER.First_name, USER.Last_name", "PLAYLIST, HOLDS, SONG, USER",
       "SONG.Name = 'Chicken Nugget Piano' AND SONG.Song_ID = HOLDS.Song_ID AND HOLDS.Playlist_ID = PLAYLIST.Playlist_ID AND PLAYLIST.User_ID = USER.User_ID")

conn.commit()
conn.close()
