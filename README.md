## Контроллеры
- developer - CRUD для разработчика
- genre - CRUD для жанров
- game - CRUD для игр
## Роутинг
### game
- **GET** - /games/{id} (без id выведет все игры в таблице game)  
Ответ в JSON:  
`{
   "id": game_id,
   "name": game_name,
   "developer": {
      "id": developer_id,
      "name": developer_name
   },
   "genres": [
      {
        "id": genre_id,
        "name": genre_name
      }
   ]
}`
- **POST** - /games  
JSON для добавления:  
`{
  "name": game_name,
  "dev": developer_id,
  "gens": [generes_id]
}`
- **DELETE** - /games/{id}  
- **PUT** - /games/{id}  
JSON для редактирования:  
`{
  "name": game_name,
  "dev": developer_id,
  "gens": [generes_id]
}`
- **ВЫВОД ИГР С ОПРЕДЕЛЕННЫМ ЖАНРОМ (GET)** - /games/genre?genre_id={genre_id}  
Ответ в JSON:  
`{
  "id": game_id,
  "name": game_name,
  "developer": {
    "id": developer_id,
    "name": developer_name
  },
  "genres": [
    {
      "id": genre_id,
      "name": genre_name
    }
  ]
}`
### developer и genre (примеры только с developer)
- **GET** - /developers/{id} (без id выведет всех разработчиков в таблице developer)  
Ответ в JSON:  
`{
  "id": developer_id,
  "name": developer_name
}`
- **POST** - /developers  
JSON для добавления:  
`{
  "name": developer_name
}`
- **DELETE** - /developers/{id}
- **PUT** - /developers/{id}  
JSON для редактирования:  
`{
  "name": developer_name
}`