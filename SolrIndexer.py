import urllib2
import json
import csv
from datetime import date


#db_output_url = '<BASE URL OF YOUR DATA CATALOG INSTALLATION>/api/dataset/all.json'
#submit_url = '<BASE URL OF YOUR SOLR INSTALLATION>/solr/data_catalog/update/json?commit=true&overwrite=true'


db_output_url = 'http://testbox1.hsls.pitt.edu/data/api/dataset/all.json'
solr_output_url = 'http://testbox1.hsls.pitt.edu:8983/solr/collection1/select/?q=*:*&wt=json'
submit_url = 'http://testbox1.hsls.pitt.edu:8983/solr/collection1/update/json?commit=true&overwrite=true'
remove_url = 'http://testbox1.hsls.pitt.edu:8983/solr/collection1/update/?commit=true'

db_response = urllib2.urlopen(db_output_url)
db_json_output = db_response.read()
db_parsed_json=json.loads(db_json_output)

solr_response = urllib2.urlopen(solr_output_url)
solr_json_output = solr_response.read()

#print solr_data['response']['docs'][0]

#
# Find items to remove

for row in json.loads(solr_json_output)['response']['docs']:
  if not [x for x in db_parsed_json if x['id'] == int(row['id'])]:
    
    to_solr="{'delete': {'id': "+row['id']+"}}"
    print "delete "+row['id']
    request = urllib2.Request(remove_url, to_solr.encode("utf-8"), {'Content-Type': 'application/json'})
    try:
      urllib2.urlopen(request)
    except urllib2.HTTPError as e:
      print e.code
      print e.read()



#
# Find new/added items

for row in db_parsed_json:

#  print row['id']
#  if [x for x in solr_data['response']['docs'] if x['id'] == row['id']]:
#    print 'found'

  if (row['dataset_end_date'] and row['dataset_start_date']):
    end = row['dataset_end_date']
    if (end == 'Present'):
      end = date.today().year + 1
    end = int(end)
    start = int(row['dataset_start_date'])
    if start == end:
      row['dataset_years'] = [str(start)+'-01-01T00:00:00Z',str(end)+'-01-01T00:00:00Z']
    else:
      years = range(start,end)
      years = [str(v)+'-01-01T00:00:00Z' for v in years]
      row['dataset_years'] = years
  for item in row:
    if not row[item]:
      row[item] = ''
  if (row['date_added']):
    from_symfony = row['date_added']['date']
    solr_date = from_symfony.split()[0].strip() + 'T00:00:00Z'
    row['date_added'] = solr_date
  to_solr = json.dumps(row)
  to_solr = "[" + to_solr + "]"
  print to_solr
  #print row["id"]
  request = urllib2.Request(submit_url, to_solr.encode("utf-8"), {'Content-Type': 'application/json'})


  try:
    urllib2.urlopen(request)
  except urllib2.HTTPError as e:
    print e.code
    print e.read()



submit_url = 'http://testbox1.hsls.pitt.edu:8983/solr/collection1/update/json?commit=true&overwrite=true'

