<?php




// one node
class ShanNode
{
    public $left = NULL;              //the left branch child node or NULL
    public $right = NULL;             //the right branch child node or NULL
    public $parent = NULL;            //the parent node, or NULL only for the root node
    public $symbol = NULL;            //the symbol, only in use when node is a leaf
}
//end of class



// main "public" Shannon-Fano encryption and decryption class
// 
class Shannon
{
    private $root;                    //tree root, a ShanNode class instance
    private $char_list;               //array of the leaf nodes, indexed by the symbol
    
    private $bit_container=0;
    private $bit_pos=0;
                        
    private $read_array;              //used when reading from byte array
    private $read_pos=0;
    
    
    private $rtn;                     //used to output the byte array when compressing                
    private $txt;
    


    //convert input text to a byte array
    function textToArray($text)
    {
        $ar = array_slice(unpack("C*", "\0".$text), 1);
        return $ar;
    }


    // generate a frequncy table then sort it
    //
    // returns a byte array containing:-
    //
    // split point
    // the symbols
    //
    function calcFTab($byte_array)
    {
        $rtn = array();
        $ftab = array();




    //create an empty frequency tab array, with value set to 0, for each symbol
        $len = count($byte_array);
    
        for($i=0;$i<$len;$i++)
        {
           $ftab[(string)$byte_array[$i]]=0;
        }

        //for each symbol, increment the frequency tab entry
        //
        for($i=0;$i<$len;$i++)
        {
            $ftab[(string)$byte_array[$i]]++;
        }

        //sort the array by value, most common symbols at start
        arsort($ftab);

        //compute the total frequency
        $total_freq = 0;
        while($element = current($ftab))
        {
            $total_freq += current($ftab);
            next($ftab);
        }

        //calculate the split point
        $max=0;
        $split=0;
        foreach ($ftab as $key1 => $value1)
        {
            $max += $value1;
            $split++;
            if($max >= $total_freq/2)
            {
                //half of the total freq is used, so stop
                break;
            }
        }

        //output the final array 
        //split point  (array pos 0)
        //symbols (array pos 1-)
        
        array_push($rtn,$split);

        foreach ($ftab as $key1 => $value1)
        {
            array_push($rtn,$key1);
        }

        
        return $rtn;
    }
    
    
    //insert node 
    function insertTreeNodeForChar($curpos,$num_remaining,$charnum,$split)
    {

        if($charnum==1)
        {
                //first character use the initial midpoint
                $num_left = $split;

        }
        else
        {
                //after first character
                $num_left = $num_remaining / 2;
                //round to integer
                $num_left=round($num_left);

        }

        $num_right = $num_remaining - $num_left;

            //insert a left and right branch

        $left = new ShanNode();
        $left->parent = $curpos;
        $curpos->left = $left;

        if($num_left > 1)
        {
            $this->insertTreeNodeForChar($left,$num_left,$charnum+1,$split);
        }
        else
        {
            $left->symbol = $this->readUByte();
            
            $this->char_list[$left->symbol] = $left;
        }

        $right = new ShanNode();
        $right->parent = $curpos;
        $curpos->right = $right;

        if($num_right > 1)
        {
            $this->insertTreeNodeForChar($right,$num_right,$charnum+1,$split);

        }
        else
        {
            $right->symbol = $this->readUByte();
            
            $this->char_list[$right->symbol] = $right;
        }
    }


    
    // create tree using an array containing:-
    // split point
    // the symbols
    //
    function buildTree($freq_tab_data, $num_symbols)
    {
    
    
        //store the frequency table array in read_array
        //
        $this->read_array = $freq_tab_data;
        $this->read_pos=0;
    

        $sroot = new ShanNode();
        

       //read split point, the first member of the array
        $split = $this->readUByte();


        //store the array of symbols in the class instance
        //where each can be assigned to a tree leaf
        
        //and insert the text starting in the root node
        $this->insertTreeNodeForChar($sroot, $num_symbols, 1, $split);

        return $sroot;
    }
    

    //write an integer to a byte array
    //
    //bits 0-6   = bits 0-6
    //bit  7     = 1 if >=128  (2^7)
    //bits 8-14  = bits 7-13
    //bit  15    = 1 if >=16384 (2^14)
    //bits 16-31 = bits 14-29  
    //
    function writeVal($val)
    {
        if($val<0)
        {
          //negative number ignored
        
        
        }
        else if($val<128)
        {
            //write bits 0-6 in a single byte
            //
            array_push($this->rtn,$val);
        }
        else if($val<16384)
        {
            //write bits 0-13 in two bytes
            //
            
            $v1=$val & 127;
            $v1=$v1 | 128;
            $v2=$val>>7;
             
            array_push($this->rtn,$v1);
            array_push($this->rtn,$v2);
        }
        else
        {
             //val >= 16384
             //so write bits 0-29 from val
             //in 4 bytes (32 bits)
             
             
             $v1=$val & 127;
             $v1=$v1 | 128;
             $v2=$val>>7;
             $v2=$v2 | 128;
             
            array_push($this->rtn,$v1);
            array_push($this->rtn,$v2);
            
            
            //and use bits 14- in top two bytes
            
             $v3=$val>>14;
             $v4=$val>>22;
            
            array_push($this->rtn,$v3);
            array_push($this->rtn,$v4);
        
        }
    }
    
    
    //read from read_array
    function readUByte()
    {
        $b=$this->read_array[$this->read_pos];
        $this->read_pos++;
        return $b;
    }
    
    
    //read an integer from $this->read_array
    //
    //bits 0-6   = bits 0-6
    //bit  7     = 1 if >=128  (2^7)
    //bits 8-14  = bits 7-13
    //bit  15    = 1 if >=16384 (2^14)
    //bits 16-31 = bits 14-29  
    //
    function readVal()
    {
        $rtn = 0;
        $b = 0;

        
        $b = $this->readUByte();
        
        //if bit 7=0, use bits 0-6
        
        if (($b & 128)==0)
        {
            //bits 0-6
            return $b;
        }
        
        //remove bit 7 (we know it was 1)
        $b=$b & 127;
        
        //read next byte
        $b2 = $this->readUByte();
        
        //if bit 15=, use bits 0-6 and 8-14
        
        
        if(($b2 & 128)==0)
        {
            $b2 <<=7;    //use bits 7-14 as
            $b |= $b2;
            return $b;
        }
        //remove bit 15 (we know it was 1)
        $b2=$b2 & 127;
                
        $b2 <<=7;    //use bits 7-14 as
        $b |= $b2;
            
        //read next 2 bytes
        $b3 = $this->readUByte();
        $b4 = $this->readUByte();
        
        $b3 <<=14;
        $b4 <<=22;
    
        $b |= $b3;
        $b |= $b3;
    
        return $b;
        
    }


    //write unsigned byte to $this->rtn
    function writeUByte($val)
    {
         array_push($this->rtn,$val);
    }
    
    
    //output 0 or 1 when compressing
    //
    function output1Bit($bit)
    {
        if($this->bit_pos==8 || $bit==-1)
        {
             //bit container is full OR write any remaining bits at the end
             while($this->bit_pos<8)
             {
                
                $this->bit_container<<=1;
                $this->bit_pos++;
             }
            
            $this->writeUByte($this->bit_container);            
            $this->bit_pos=0;
            $this->bit_container=0;
        }
        
        
        $this->bit_container=($this->bit_container<<1) | $bit;

        $this->bit_pos++;
    }



    function compressByte($cur_node, $child)
    {
    
         //get back to root
         if(!is_null($cur_node->parent))
         {
             $this->compressByte($cur_node->parent, $cur_node);
         }
                

         if(!is_null($child))
         {
             if ($cur_node->right === $child)
             {
                 return $this->output1Bit(0);
             }
             if ($child === $cur_node->left)
             {
                 return $this->output1Bit(1);
             }
         }
    }
    

    // Compress text, returns a byte array
    //
    public function compressText($text)
    {
        //get the text as a byte array
        $src_bin = $this->textToArray($text);
    
    	return $this->compressBin($src_bin);
    }


    // Compress binary byte array
    // returns a byte array
    //
    public function compressBin($byte_array)
    {
    
        //compute frequency table
        //return an array containing:-
        //split point
        //the symbols, ordered by frequency
        //
        $a = $this->calcFTab($byte_array);

        //get number of symbols (array len-1)
        $num_symbols=count($a)-1;

        //and build the tree
        $this->root=$this->buildTree($a,$num_symbols);
        
        //create the output byte array
        $this->rtn = array();
        
        //write number of symbols
        $this->writeUByte($num_symbols);
        
        //write the split point (an unsigned byte)
        $this->writeUByte($a[0]);
        
        //and write the symbols
        $i=1;
        $len = count($a);
        
        while($i < $len)
        {
            $this->writeUByte($a[$i]);
            $i++;
        }
        
        //write the number of characters in the source data
        $this->writeVal(count ($byte_array) );
 

        //compress the text into $this->rtn
        
        $this->bit_container = 0;
        $this->bit_pos = 0;
        
        $len = count($byte_array);
        for($i=0;$i < $len ;$i++)
        {
            $cur_symbol = $byte_array[$i];
            
            //find the leaf node for the current symbol
            $cur_leaf=$this->char_list[$cur_symbol];
            
            if(!is_null($cur_leaf))
            {
                $this->compressByte($cur_leaf, NULL);
            }
        }
        
        //flush the bit container, any bits remaining are written
        $this->output1Bit(-1);        
        
        // finally return the byte array
        return $this->rtn;
    }
    
    
    // 
    // decompress a previously compressed byte array
    //
    public function expandText($byte_array)
    {
        //build tree using byte array containing:-
        //number of symbols
        //split point
        //the symbols 
        
            
        $num_symb = array_shift($byte_array);
        
        
        //awway now contains split point then the symbols

        $this->root = $this->buildTree($byte_array,$num_symb);
        
        
        $num_chars = $this->readVal();
        
        
        //initialise the decoded text
        
        $i = 0;
        $bit_container = 0;
        $bit_pos = 0;
        
        $res="";
    
        while($i<$num_chars)
        {
        
            $curpos = $this->root;
            while(!is_null($curpos->right))
            {
                if($bit_pos==0)
                {
                            //read a new byte into the bitcontainer
                            
                    $bit_container = $this->readUByte();    
                    $bit_pos = 8;        
                }

                //use top bit to decide which way to move DOWN
                if(($bit_container & 128)!=0)
                {
                    $curpos = $curpos->left;
                }
                else
                {
                    $curpos = $curpos->right;
                }

                //shift bit container left
                $bit_container <<= 1;
                $bit_pos--;
            }
            
            //the node at curpos is a leaft, with the decoded symbol
            //so, append the decoded character
            $res.= chr($curpos->symbol);

            //next character            
            $i++;    
        }
        
        return $res;
    }

    
    
    // 
    // decompress a previously compressed byte array
    //
    public function expandBin($byte_array)
    {
        
        //build tree using byte array containing:-
        //number of symbols
        //split point
        //the symbols 
        
            
        $num_symb = array_shift($byte_array);
        
        
        //awway now contains split point then the symbols

        $this->root = $this->buildTree($byte_array,$num_symb);
        
        
        $num_chars = $this->readVal();
        
        
        //initialise the decoded text
        
        $i = 0;
        $bit_container = 0;
        $bit_pos = 0;
        
        $res = array();
    
        while($i<$num_chars)
        {
        
            $curpos = $this->root;
            while(!is_null($curpos->right))
            {
                if($bit_pos==0)
                {
                            //read a new byte into the bitcontainer
                            
                    $bit_container = $this->readUByte();    
                    $bit_pos = 8;        
                }

                //use top bit to decide which way to move DOWN
                if(($bit_container & 128)!=0)
                {
                    $curpos = $curpos->left;
                }
                else
                {
                    $curpos = $curpos->right;
                }

                //shift bit container left
                $bit_container <<= 1;
                $bit_pos--;
            }
            
            //the node at curpos is a leaft, with the decoded symbol
            array_push($res,$curpos->symbol);

            //next character            
            $i++;    
        }
        
        return $res;
    }

}
//end of class

    $test = new Shannon;
    $test->textToArray('abc');

    // $test2 = new Shannon;
    // var_dump($test2->calcFTab('D'));

        var_dump($test->calcFTab($test[0]));

    // for ($i=0; $i < count($test); $i++) { 
    //     var_dump($test[$i]->calcFTab($test[$i]));
    // }

?>