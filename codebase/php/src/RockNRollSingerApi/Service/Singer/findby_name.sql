select  *

from    singer

where   (upper(name) = upper(:name))
