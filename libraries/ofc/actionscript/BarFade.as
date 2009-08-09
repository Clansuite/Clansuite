﻿class BarFade extends BarStyle
{
	public function BarFade( lv:Object, name:String )
	{
		this.name = 'bar_fade'+name;
		// this calls parent obj Style.Style first
		this.parse_bar( lv[this.name] );
		this.set_values( lv['values'+name], lv['links'+name], lv['tool_tips_set'+name] );
	}
	
	public function draw_bar( val:PointBar, i:Number )
	{
		var mc:MovieClip = this.bar_mcs[i];
		mc.clear();
		
		var top:Number;
		var height:Number;
		
		if(val.bar_bottom<val.y)
		{
			top = val.bar_bottom;
			height = val.y-val.bar_bottom;
		}
		else
		{
			top = val.y
			height = val.bar_bottom-val.y;
		}
		
		//set gradient fill
		var colors:Array = [this.colour,0xFFFFFF];
		var alphas:Array = [100,0];
		var ratios:Array = [0,255];
		var matrix:Object = { matrixType:"box", x:0, y:0, w:val.width, h:height, r:(90/180)*Math.PI };
		mc.beginGradientFill("linear", colors, alphas, ratios, matrix);
		
		
		//mc.beginFill( this.colour, 100 );
		mc.moveTo( 0, 0 );
    	mc.lineTo( val.width, 0 );
    	mc.lineTo( val.width, height );
    	mc.lineTo( 0, height );
		mc.lineTo( 0, 0 );
    	mc.endFill();
		
		mc._x = val.x;
		mc._y = top;
		
		mc._alpha = this.alpha;
		mc._alpha_original = this.alpha;	// <-- remember our original alpha while tweening
		
		// this is used in _root.FadeIn and _root.FadeOut
		//mc.val = val;
		
		// we return this MovieClip to FilledBarStyle
		return mc;
	}
}
